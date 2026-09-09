<?php

namespace App\Console\Commands;

use App\Models\InstagramAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RefreshInstagramToken extends Command
{
    protected $signature = 'instagram:refresh-token
                            {--force : Attempt refresh even when the token is not near expiry}';

    protected $description = 'Refresh the long-lived Instagram access token';

    public function handle(): int
    {
        $account = InstagramAccount::query()
            ->where('active', true)
            ->first();

        if (!$account) {
            $this->error('No active Instagram account was found.');

            return self::FAILURE;
        }

        if (empty($account->access_token)) {
            $this->error('The Instagram access token is missing.');

            return self::FAILURE;
        }

        /*
         * Meta requires a long-lived token to be at least
         * 24 hours old before it can be refreshed.
         */
        if (
            $account->last_refreshed_at &&
            $account->last_refreshed_at->greaterThan(
                now()->subHours(24)
            )
        ) {
            $nextRefreshTime = $account->last_refreshed_at
                ->copy()
                ->addHours(24)
                ->toDateTimeString();

            $this->info(
                "Token is less than 24 hours old. Try again after {$nextRefreshTime}."
            );

            return self::SUCCESS;
        }

        /*
         * The daily scheduler only refreshes when the token
         * has 20 days or fewer remaining.
         *
         * --force bypasses this particular check.
         */
        if (
            !$this->option('force') &&
            $account->token_expires_at &&
            $account->token_expires_at->greaterThan(
                now()->addDays(20)
            )
        ) {
            $daysRemaining = now()->diffInDays(
                $account->token_expires_at,
                false
            );

            $this->info(
                "Token does not need renewal yet. Approximately {$daysRemaining} days remain."
            );

            return self::SUCCESS;
        }

        $account->update([
            'last_refresh_attempt_at' => now(),
            'last_refresh_error' => null,
        ]);

        try {
            $response = Http::acceptJson()
                ->timeout(30)
                ->retry(2, 1000)
                ->get(
                    'https://graph.instagram.com/refresh_access_token',
                    [
                        'grant_type' => 'ig_refresh_token',
                        'access_token' => $account->access_token,
                    ]
                );

            if ($response->failed()) {
                $message = $response->json('error.message')
                    ?? $response->body()
                    ?? 'Unknown Instagram API error.';

                throw new \RuntimeException($message);
            }

            $data = $response->json();

            $newToken = $data['access_token'] ?? null;
            $expiresIn = (int) ($data['expires_in'] ?? 0);

            if (!$newToken) {
                throw new \RuntimeException(
                    'Instagram did not return a refreshed access token.'
                );
            }

            if ($expiresIn <= 0) {
                throw new \RuntimeException(
                    'Instagram did not return a valid token expiry period.'
                );
            }

            $account->update([
                'access_token' => $newToken,
                'token_expires_at' => now()->addSeconds($expiresIn),
                'last_refreshed_at' => now(),
                'last_refresh_attempt_at' => now(),
                'last_refresh_error' => null,
            ]);

            /*
             * Remove the current feed cache so the controller
             * begins using the refreshed token.
             */
            Cache::forget('instagram_feed.current');

            Log::info('Instagram access token refreshed successfully.', [
                'instagram_account_id' => $account->id,
                'instagram_user_id' => $account->instagram_user_id,
                'expires_at' => $account->token_expires_at?->toIso8601String(),
            ]);

            $this->info('Instagram token refreshed successfully.');
            $this->line(
                'New expiry: ' .
                $account->token_expires_at?->toDateTimeString()
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);

            $account->update([
                'last_refresh_attempt_at' => now(),
                'last_refresh_error' => $exception->getMessage(),
            ]);

            Log::error('Instagram token refresh failed.', [
                'instagram_account_id' => $account->id,
                'instagram_user_id' => $account->instagram_user_id,
                'error' => $exception->getMessage(),
            ]);

            $this->error(
                'Instagram token refresh failed: ' .
                $exception->getMessage()
            );

            return self::FAILURE;
        }
    }
}
