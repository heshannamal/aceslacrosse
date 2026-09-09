<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class InstagramFeedController extends Controller
{
    /**
     * Return the Instagram profile and latest posts.
     */
    public function index(): JsonResponse
    {
        $username = $this->expectedUsername();

        $cacheKey = 'instagram.feed.' . $username;
        $staleCacheKey = 'instagram.feed.stale.' . $username;

        /*
         * Return the normal cache first.
         */
        $cachedFeed = Cache::get($cacheKey);

        if (is_array($cachedFeed)) {
            return response()->json($cachedFeed);
        }

        try {
            $payload = $this->buildFeedPayload();

            /*
             * Main cache: 30 minutes by default.
             */
            Cache::put(
                $cacheKey,
                $payload,
                now()->addMinutes($this->cacheMinutes())
            );

            /*
             * Stale cache: used when Meta temporarily fails.
             */
            Cache::put(
                $staleCacheKey,
                $payload,
                now()->addDays(7)
            );

            return response()->json($payload);
        } catch (Throwable $exception) {
            Log::error('Aces Instagram feed failed.', [
                'username' => $username,
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
            ]);

            /*
             * Return the previous successful feed when available.
             */
            $staleFeed = Cache::get($staleCacheKey);

            if (is_array($staleFeed)) {
                $staleFeed['stale'] = true;
                $staleFeed['message'] =
                    'Instagram is temporarily unavailable. Showing cached posts.';

                return response()->json($staleFeed);
            }

            return response()->json([
                'success' => false,
                'message' => config('app.debug')
                    ? $exception->getMessage()
                    : 'Instagram feed is temporarily unavailable.',
                'profile' => $this->fallbackProfile(),
                'items' => [],
                'stale' => false,
            ], 503);
        }
    }

    /**
     * Build the response consumed by the Blade component.
     */
    private function buildFeedPayload(): array
    {
        $this->validateConfiguration();

        $profileResponse = $this->fetchProfile();
        $mediaResponse = $this->fetchMedia();

        $items = collect($mediaResponse['data'] ?? [])
            ->map(function (array $media): ?array {
                return $this->normalizeMedia($media);
            })
            ->filter()
            ->take($this->postLimit())
            ->values()
            ->all();

        $profile = $this->normalizeProfile(
            $profileResponse,
            count($items)
        );

        $this->validateConnectedAccount($profile);

        return [
            'success' => true,
            'message' => null,
            'profile' => $profile,
            'items' => $items,
            'stale' => false,
            'generated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Fetch Instagram profile information.
     *
     * It tries multiple field sets because available fields can differ
     * depending on whether the token came from Instagram Login or
     * Facebook Login.
     */
    private function fetchProfile(): array
    {
        $fieldSets = [
            [
                'id',
                'username',
                'name',
                'profile_picture_url',
                'followers_count',
                'follows_count',
                'media_count',
            ],
            [
                'id',
                'username',
                'name',
                'profile_picture_url',
                'followers_count',
                'media_count',
            ],
            [
                'id',
                'username',
                'media_count',
            ],
            [
                'id',
                'username',
            ],
        ];

        $lastException = null;

        foreach ($fieldSets as $fields) {
            try {
                return $this->graphGet(
                    $this->instagramUserId(),
                    [
                        'fields' => implode(',', $fields),
                    ]
                );
            } catch (Throwable $exception) {
                $lastException = $exception;

                Log::warning(
                    'Instagram profile field set was rejected.',
                    [
                        'fields' => $fields,
                        'message' => $exception->getMessage(),
                    ]
                );
            }
        }

        throw $lastException ?? new RuntimeException(
            'Instagram profile could not be loaded.'
        );
    }

    /**
     * Fetch the latest Instagram media.
     */
    private function fetchMedia(): array
    {
        $userId = $this->instagramUserId();

        $fieldSets = [
            [
                'id',
                'caption',
                'media_type',
                'media_product_type',
                'media_url',
                'thumbnail_url',
                'permalink',
                'timestamp',
                'like_count',
                'comments_count',
                'children{media_type,media_url,thumbnail_url}',
            ],
            [
                'id',
                'caption',
                'media_type',
                'media_url',
                'thumbnail_url',
                'permalink',
                'timestamp',
                'children{media_type,media_url,thumbnail_url}',
            ],
            [
                'id',
                'caption',
                'media_type',
                'media_url',
                'thumbnail_url',
                'permalink',
                'timestamp',
            ],
            [
                'id',
                'media_type',
                'media_url',
                'thumbnail_url',
                'permalink',
            ],
        ];

        $lastException = null;

        foreach ($fieldSets as $fields) {
            try {
                return $this->graphGet(
                    $userId . '/media',
                    [
                        'fields' => implode(',', $fields),
                        'limit' => max($this->postLimit(), 12),
                    ]
                );
            } catch (Throwable $exception) {
                $lastException = $exception;

                Log::warning(
                    'Instagram media field set was rejected.',
                    [
                        'fields' => $fields,
                        'message' => $exception->getMessage(),
                    ]
                );
            }
        }

        throw $lastException ?? new RuntimeException(
            'Instagram posts could not be loaded.'
        );
    }

    /**
     * Send a GET request to the configured Graph API.
     */
    private function graphGet(
        string $endpoint,
        array $query = []
    ): array {
        $url = $this->graphUrl($endpoint);

        $response = Http::acceptJson()
            ->timeout(20)
            ->connectTimeout(10)
            ->retry(2, 500)
            ->get($url, array_merge($query, [
                'access_token' => $this->accessToken(),
            ]));

        if ($response->failed()) {
            throw new RuntimeException(
                $this->extractGraphError($response)
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'Instagram returned an invalid response.'
            );
        }

        if (isset($data['error'])) {
            throw new RuntimeException(
                $data['error']['message']
                    ?? 'Instagram returned an unknown error.'
            );
        }

        return $data;
    }

    /**
     * Normalize the Instagram profile for the frontend.
     */
    private function normalizeProfile(
        array $profile,
        int $loadedPostCount
    ): array {
        $username = ltrim(
            (string) ($profile['username'] ?? $this->expectedUsername()),
            '@'
        );

        return [
            'id' => $profile['id'] ?? null,

            'name' => $profile['name']
                ?? config('services.instagram.name', 'Aces Lacrosse'),

            'username' => $username,

            'profile_image' => $profile['profile_picture_url']
                ?? asset('images/logo.png'),

            'posts' => (int) (
                $profile['media_count']
                ?? $loadedPostCount
            ),

            'followers' => (int) (
                $profile['followers_count']
                ?? 0
            ),

            'following' => (int) (
                $profile['follows_count']
                ?? $profile['following_count']
                ?? 0
            ),

            'url' => 'https://www.instagram.com/' . $username,
        ];
    }

    /**
     * Normalize an Instagram media item.
     */
    private function normalizeMedia(array $media): ?array
    {
        $permalink = $media['permalink'] ?? null;
        $imageUrl = $this->resolveMediaImage($media);

        if (!$permalink || !$imageUrl) {
            return null;
        }

        return [
            'id' => $media['id'] ?? null,

            'caption' => $media['caption'] ?? '',

            'media_type' => $media['media_type'] ?? 'IMAGE',

            'media_product_type' =>
                $media['media_product_type'] ?? null,

            /*
             * Your Blade requires this normalized field.
             */
            'image_url' => $imageUrl,

            /*
             * Retain the original API fields as well.
             */
            'media_url' => $media['media_url'] ?? null,

            'thumbnail_url' =>
                $media['thumbnail_url'] ?? null,

            'permalink' => $permalink,

            'timestamp' => $media['timestamp'] ?? null,

            'like_count' => isset($media['like_count'])
                ? (int) $media['like_count']
                : null,

            'comments_count' => isset($media['comments_count'])
                ? (int) $media['comments_count']
                : null,
        ];
    }

    /**
     * Determine the correct image for images, videos, Reels
     * and carousel posts.
     */
    private function resolveMediaImage(array $media): ?string
    {
        $mediaType = strtoupper(
            (string) ($media['media_type'] ?? '')
        );

        $productType = strtoupper(
            (string) ($media['media_product_type'] ?? '')
        );

        /*
         * Videos and Reels should use thumbnail_url instead of
         * attempting to display the MP4 media_url as an image.
         */
        if (
            $mediaType === 'VIDEO'
            || $productType === 'REELS'
        ) {
            return $media['thumbnail_url']
                ?? $this->resolveCarouselImage($media)
                ?? $media['media_url']
                ?? null;
        }

        return $media['media_url']
            ?? $media['thumbnail_url']
            ?? $this->resolveCarouselImage($media)
            ?? null;
    }

    /**
     * Get the first usable image from a carousel.
     */
    private function resolveCarouselImage(array $media): ?string
    {
        $children = $media['children']['data']
            ?? $media['children']
            ?? [];

        if (!is_array($children)) {
            return null;
        }

        foreach ($children as $child) {
            if (!is_array($child)) {
                continue;
            }

            $childType = strtoupper(
                (string) ($child['media_type'] ?? '')
            );

            if ($childType === 'VIDEO') {
                $image = $child['thumbnail_url']
                    ?? $child['media_url']
                    ?? null;
            } else {
                $image = $child['media_url']
                    ?? $child['thumbnail_url']
                    ?? null;
            }

            if ($image) {
                return $image;
            }
        }

        return null;
    }

    /**
     * Ensure the API token belongs to aceslacrosseclub.
     */
    private function validateConnectedAccount(array $profile): void
    {
        $expected = strtolower(
            ltrim($this->expectedUsername(), '@')
        );

        $actual = strtolower(
            ltrim((string) ($profile['username'] ?? ''), '@')
        );

        if ($actual !== '' && $actual !== $expected) {
            throw new RuntimeException(
                "The access token belongs to @{$actual}, "
                . "but this website expects @{$expected}."
            );
        }
    }

    /**
     * Validate required environment configuration.
     */
    private function validateConfiguration(): void
    {
        if ($this->accessToken() === '') {
            throw new RuntimeException(
                'INSTAGRAM_ACCESS_TOKEN is not configured.'
            );
        }

        if ($this->instagramUserId() === '') {
            throw new RuntimeException(
                'INSTAGRAM_USER_ID is not configured.'
            );
        }
    }

    /**
     * Construct the Graph API URL.
     */
    private function graphUrl(string $endpoint): string
    {
        $baseUrl = rtrim(
            (string) config(
                'services.instagram.base_url',
                'https://graph.instagram.com'
            ),
            '/'
        );

        $version = trim(
            (string) config(
                'services.instagram.version',
                'v22.0'
            ),
            '/'
        );

        $endpoint = ltrim($endpoint, '/');

        return $baseUrl
            . '/'
            . $version
            . '/'
            . $endpoint;
    }

    /**
     * Extract a safe error message from Meta.
     */
    private function extractGraphError(Response $response): string
    {
        $message = $response->json('error.message')
            ?? $response->json('message')
            ?? 'Instagram API request failed.';

        return $message . ' (HTTP ' . $response->status() . ')';
    }

    /**
     * Return basic profile information when the API fails.
     */
    private function fallbackProfile(): array
    {
        return [
            'id' => null,
            'name' => config(
                'services.instagram.name',
                'Aces Lacrosse'
            ),
            'username' => $this->expectedUsername(),
            'profile_image' => asset('images/logo.png'),
            'posts' => 0,
            'followers' => 0,
            'following' => 0,
            'url' => 'https://www.instagram.com/'
                . $this->expectedUsername(),
        ];
    }

    private function accessToken(): string
    {
        return trim(
            (string) config(
                'services.instagram.access_token',
                ''
            )
        );
    }

    private function instagramUserId(): string
    {
        return trim(
            (string) config(
                'services.instagram.user_id',
                'me'
            )
        );
    }

    private function expectedUsername(): string
    {
        return ltrim(
            trim(
                (string) config(
                    'services.instagram.username',
                    'aceslacrosseclub'
                )
            ),
            '@'
        );
    }

    private function postLimit(): int
    {
        return max(
            1,
            min(
                (int) config(
                    'services.instagram.post_limit',
                    8
                ),
                25
            )
        );
    }

    private function cacheMinutes(): int
    {
        return max(
            1,
            (int) config(
                'services.instagram.cache_minutes',
                30
            )
        );
    }
}
