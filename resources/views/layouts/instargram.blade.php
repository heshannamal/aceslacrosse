<!-- Elfsight Instagram Feed | Untitled Instagram Feed -->
{{-- <script src="https://elfsightcdn.com/platform.js" async></script>
<section class="instagram-section mb-3">
    <div class="elfsight-app-b3995797-676b-4610-bc87-e57fd657a1c4" data-elfsight-app-lazy></div>
</section> --}}


{{-- =========================================================
     ACES LACROSSE INSTAGRAM FEED
     API: /api/instagram-feed
========================================================= --}}

@php
    $acesInstagramUsername = $instagramUsername ?? 'aceslacrosseclub';
    $acesInstagramName = $instagramName ?? 'Aces Lacrosse';
    $acesInstagramUrl = $instagramUrl
        ?? 'https://www.instagram.com/' . $acesInstagramUsername;
    $acesInstagramFeedUrl = $feedUrl
        ?? url('/api/instagram-feed');
    $acesInstagramFallbackImage = $fallbackImage
        ?? asset('public/android-chrome-512x512.png');
@endphp

<section id="aces-instagram-section" class="aces-instagram-section">

    {{-- Profile header --}}
    <div class="aces-instagram-header">
        <div class="aces-instagram-profile-container">

            {{-- Profile information --}}
            <a
                id="aces-instagram-profile-link"
                href="{{ $acesInstagramUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="aces-instagram-profile-details"
            >
                <img
                    id="aces-instagram-profile-image"
                    src="{{ $acesInstagramFallbackImage }}"
                    alt="Aces Lacrosse"
                    class="aces-instagram-profile-image"
                >

                <div class="aces-instagram-profile-text">
                    <h2
                        id="aces-instagram-profile-name"
                        class="aces-instagram-profile-name"
                    >
                        Aces Lacrosse
                    </h2>

                    <span
                        id="aces-instagram-profile-username"
                        class="aces-instagram-profile-username"
                    >
                        @aceslacrosseclub
                    </span>
                </div>
            </a>

            {{-- Account statistics --}}
            <div class="aces-instagram-statistics">
                <div class="aces-instagram-stat">
                    <strong id="aces-instagram-posts-count">0</strong>
                    <span>Posts</span>
                </div>

                <div class="aces-instagram-stat">
                    <strong id="aces-instagram-followers-count">0</strong>
                    <span>Followers</span>
                </div>

                <div class="aces-instagram-stat">
                    <strong id="aces-instagram-following-count">0</strong>
                    <span>Following</span>
                </div>
            </div>

            {{-- Follow button --}}
            <a
                id="aces-instagram-follow-link"
                href="{{ $acesInstagramUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="aces-instagram-follow-button"
            >
                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                    class="aces-instagram-follow-icon"
                >
                    <rect
                        x="2"
                        y="2"
                        width="20"
                        height="20"
                        rx="5"
                        ry="5"
                    ></rect>

                    <path
                        d="M16 11.37A4 4 0 1 1 12.63 8
                           4 4 0 0 1 16 11.37z"
                    ></path>

                    <line
                        x1="17.5"
                        y1="6.5"
                        x2="17.51"
                        y2="6.5"
                    ></line>
                </svg>

                <span>Follow</span>
            </a>

        </div>
    </div>

    {{-- Feed grid --}}
    <div id="aces-instagram-feed" class="aces-instagram-grid">
        @for ($index = 0; $index < 8; $index++)
            <div class="aces-instagram-skeleton"></div>
        @endfor
    </div>

    {{-- Error/empty message --}}
    <div
        id="aces-instagram-message"
        class="aces-instagram-message"
        hidden
    ></div>

</section>

<style>
    .aces-instagram-section {
        width: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: #181818;
    }

    /* Header */

    .aces-instagram-header {
        width: 100%;
        padding: 20px 24px;
        background: #181818;
        color: #ffffff;
    }

    .aces-instagram-profile-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 60px;
        width: 100%;
        max-width: 880px;
        margin: 0 auto;
    }

    .aces-instagram-profile-details {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        gap: 14px;
        color: inherit;
        text-decoration: none;
    }

    .aces-instagram-profile-image {
        width: 58px;
        height: 58px;
        flex-shrink: 0;
        border: 2px solid #ffffff;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
    }

    .aces-instagram-profile-text {
        min-width: 205px;
    }

    .aces-instagram-profile-name {
        max-width: 215px;
        margin: 0;
        color: #ffffff;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.08;
    }

    .aces-instagram-profile-username {
        display: block;
        margin-top: 6px;
        color: #929292;
        font-size: 13px;
        line-height: 1;
    }

    /* Statistics */

    .aces-instagram-statistics {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 32px;
    }

    .aces-instagram-stat {
        min-width: 55px;
        text-align: center;
    }

    .aces-instagram-stat strong {
        display: block;
        color: #ffffff;
        font-size: 15px;
        font-weight: 800;
        line-height: 1;
    }

    .aces-instagram-stat span {
        display: block;
        margin-top: 6px;
        color: #929292;
        font-size: 12px;
        line-height: 1;
    }

    /* Follow button */

    .aces-instagram-follow-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        gap: 8px;
        min-height: 36px;
        padding: 8px 18px;
        border-radius: 5px;
        background: #0095f6;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        transition:
            background-color 0.2s ease,
            transform 0.2s ease;
    }

    .aces-instagram-follow-button:hover {
        background: #1877f2;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .aces-instagram-follow-icon {
        width: 17px;
        height: 17px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Grid */

    .aces-instagram-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        width: 100%;
        gap: 2px;
        background: #181818;
    }

    .aces-instagram-item,
    .aces-instagram-skeleton {
        position: relative;
        display: block;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #262626;
    }

    /* Loading skeleton */

    .aces-instagram-skeleton {
        background: linear-gradient(
            90deg,
            #242424 25%,
            #333333 50%,
            #242424 75%
        );
        background-size: 200% 100%;
        animation: acesInstagramSkeleton 1.3s infinite;
    }

    @keyframes acesInstagramSkeleton {
        from {
            background-position: 200% 0;
        }

        to {
            background-position: -200% 0;
        }
    }

    /* Post image */

    .aces-instagram-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .aces-instagram-item:hover .aces-instagram-image {
        transform: scale(1.05);
    }

    /* Media type icon */

    .aces-instagram-media-icon {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        pointer-events: none;
        filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.7));
    }

    .aces-instagram-media-icon svg {
        width: 23px;
        height: 23px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Hover overlay */

    .aces-instagram-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        background: rgba(0, 0, 0, 0.74);
        color: #ffffff;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .aces-instagram-item:hover .aces-instagram-overlay,
    .aces-instagram-item:focus .aces-instagram-overlay,
    .aces-instagram-item:focus-visible .aces-instagram-overlay {
        opacity: 1;
    }

    .aces-instagram-engagement {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 18px;
        margin-bottom: 22px;
    }

    .aces-instagram-engagement-item {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
    }

    .aces-instagram-engagement-item svg {
        width: 25px;
        height: 25px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .aces-instagram-caption {
        display: -webkit-box;
        width: 100%;
        max-width: 300px;
        margin: 0;
        overflow: hidden;
        color: #ffffff;
        font-size: 15px;
        font-weight: 500;
        line-height: 1.45;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    /* Message */

    .aces-instagram-message {
        padding: 35px 20px;
        background: #181818;
        color: #d1d1d1;
        text-align: center;
    }

    .aces-instagram-message a {
        color: #0095f6;
        text-decoration: none;
    }

    .aces-instagram-message a:hover {
        text-decoration: underline;
    }

    /* Tablet */

    @media (max-width: 950px) {
        .aces-instagram-profile-container {
            gap: 30px;
        }

        .aces-instagram-statistics {
            gap: 18px;
        }

        .aces-instagram-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    /* Mobile */

    @media (max-width: 700px) {
        .aces-instagram-header {
            padding: 20px 15px;
        }

        .aces-instagram-profile-container {
            flex-wrap: wrap;
            gap: 18px 25px;
        }

        .aces-instagram-profile-details {
            width: 100%;
            justify-content: center;
        }

        .aces-instagram-profile-text {
            min-width: 0;
        }

        .aces-instagram-statistics {
            order: 3;
            width: 100%;
        }

        .aces-instagram-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .aces-instagram-overlay {
            padding: 15px;
        }

        .aces-instagram-engagement {
            gap: 12px;
            margin-bottom: 12px;
        }

        .aces-instagram-engagement-item {
            font-size: 13px;
        }

        .aces-instagram-engagement-item svg {
            width: 20px;
            height: 20px;
        }

        .aces-instagram-caption {
            font-size: 12px;
            line-height: 1.35;
            -webkit-line-clamp: 2;
        }
    }
</style>

<script>
    (function () {
        'use strict';

        function initializeAcesInstagramFeed() {
            const section = document.getElementById(
                'aces-instagram-section'
            );

            const feedContainer = document.getElementById(
                'aces-instagram-feed'
            );

            const messageContainer = document.getElementById(
                'aces-instagram-message'
            );

            if (!section || !feedContainer || !messageContainer) {
                return;
            }

            if (section.dataset.instagramInitialized === 'true') {
                return;
            }

            section.dataset.instagramInitialized = 'true';

            const feedUrl = @json($acesInstagramFeedUrl);
            const expectedUsername = @json($acesInstagramUsername);
            const defaultName = @json($acesInstagramName);
            const defaultProfileUrl = @json($acesInstagramUrl);

            const profileElements = {
                image: document.getElementById(
                    'aces-instagram-profile-image'
                ),

                name: document.getElementById(
                    'aces-instagram-profile-name'
                ),

                username: document.getElementById(
                    'aces-instagram-profile-username'
                ),

                posts: document.getElementById(
                    'aces-instagram-posts-count'
                ),

                followers: document.getElementById(
                    'aces-instagram-followers-count'
                ),

                following: document.getElementById(
                    'aces-instagram-following-count'
                ),

                profileLink: document.getElementById(
                    'aces-instagram-profile-link'
                ),

                followLink: document.getElementById(
                    'aces-instagram-follow-link'
                )
            };

            function formatNumber(value) {
                const number = Number(value);

                if (!Number.isFinite(number)) {
                    return '0';
                }

                if (number >= 1000000) {
                    return (
                        (number / 1000000)
                            .toFixed(number >= 10000000 ? 0 : 1)
                            .replace('.0', '') + 'M'
                    );
                }

                if (number >= 1000) {
                    return (
                        (number / 1000)
                            .toFixed(number >= 10000 ? 0 : 1)
                            .replace('.0', '') + 'K'
                    );
                }

                return number.toLocaleString();
            }

            function showMessage(message) {
                feedContainer.innerHTML = '';
                feedContainer.style.display = 'none';
                messageContainer.innerHTML = '';

                const messageText = document.createElement('span');
                messageText.textContent =
                    (message || 'Instagram feed is unavailable.') + ' ';

                const instagramLink = document.createElement('a');
                instagramLink.href = defaultProfileUrl;
                instagramLink.target = '_blank';
                instagramLink.rel = 'noopener noreferrer';
                instagramLink.textContent = 'Visit Instagram';

                messageContainer.appendChild(messageText);
                messageContainer.appendChild(instagramLink);
                messageContainer.hidden = false;
            }

            function clearMessage() {
                feedContainer.style.display = '';
                messageContainer.hidden = true;
                messageContainer.innerHTML = '';
            }

            function normaliseUsername(value) {
                return String(value || '')
                    .trim()
                    .replace(/^@/, '');
            }

            function extractProfile(result) {
                const nestedProfile =
                    result.profile ||
                    result.account ||
                    result.user ||
                    result.instagram_profile ||
                    {};

                return {
                    name:
                        nestedProfile.name ||
                        nestedProfile.full_name ||
                        nestedProfile.display_name ||
                        result.name ||
                        defaultName,

                    username: normaliseUsername(
                        nestedProfile.username ||
                        nestedProfile.handle ||
                        result.username ||
                        expectedUsername
                    ),

                    profile_image:
                        nestedProfile.profile_image ||
                        nestedProfile.profile_picture_url ||
                        nestedProfile.avatar_url ||
                        result.profile_image ||
                        result.profile_picture_url ||
                        null,

                    posts:
                        nestedProfile.posts ??
                        nestedProfile.media_count ??
                        nestedProfile.posts_count ??
                        result.posts ??
                        result.media_count ??
                        0,

                    followers:
                        nestedProfile.followers ??
                        nestedProfile.followers_count ??
                        result.followers ??
                        result.followers_count ??
                        0,

                    following:
                        nestedProfile.following ??
                        nestedProfile.follows_count ??
                        nestedProfile.following_count ??
                        result.following ??
                        result.follows_count ??
                        0,

                    url:
                        nestedProfile.url ||
                        nestedProfile.profile_url ||
                        result.url ||
                        defaultProfileUrl
                };
            }

            function updateProfile(profile) {
                if (
                    profile.username &&
                    expectedUsername &&
                    normaliseUsername(profile.username).toLowerCase() !==
                        normaliseUsername(expectedUsername).toLowerCase()
                ) {
                    throw new Error(
                        'The Instagram API is connected to @' +
                        profile.username +
                        ', but this component expects @' +
                        expectedUsername +
                        '.'
                    );
                }

                if (
                    profile.profile_image &&
                    profileElements.image
                ) {
                    profileElements.image.src =
                        profile.profile_image;
                }

                if (profileElements.name) {
                    profileElements.name.textContent =
                        profile.name || defaultName;
                }

                if (profileElements.username) {
                    profileElements.username.textContent =
                        '@' + (
                            profile.username ||
                            expectedUsername
                        );
                }

                if (profileElements.posts) {
                    profileElements.posts.textContent =
                        formatNumber(profile.posts);
                }

                if (profileElements.followers) {
                    profileElements.followers.textContent =
                        formatNumber(profile.followers);
                }

                if (profileElements.following) {
                    profileElements.following.textContent =
                        formatNumber(profile.following);
                }

                const profileUrl =
                    profile.url || defaultProfileUrl;

                if (profileElements.profileLink) {
                    profileElements.profileLink.href =
                        profileUrl;
                }

                if (profileElements.followLink) {
                    profileElements.followLink.href =
                        profileUrl;
                }
            }

            function extractPosts(result) {
                let posts =
                    result.items ||
                    result.posts ||
                    result.media ||
                    result.data ||
                    [];

                if (
                    posts &&
                    typeof posts === 'object' &&
                    !Array.isArray(posts)
                ) {
                    posts =
                        posts.data ||
                        posts.items ||
                        posts.posts ||
                        [];
                }

                return Array.isArray(posts) ? posts : [];
            }

            function getCarouselImage(post) {
                const children =
                    post.children?.data ||
                    post.children ||
                    post.carousel_media ||
                    [];

                if (!Array.isArray(children)) {
                    return null;
                }

                for (const child of children) {
                    const image =
                        child.image_url ||
                        child.media_url ||
                        child.thumbnail_url ||
                        null;

                    if (image) {
                        return image;
                    }
                }

                return null;
            }

            function normalisePost(post) {
                if (!post || typeof post !== 'object') {
                    return null;
                }

                const mediaType = String(
                    post.media_type ||
                    post.type ||
                    ''
                ).toUpperCase();

                const productType = String(
                    post.media_product_type ||
                    ''
                ).toUpperCase();

                let imageUrl = null;

                if (
                    mediaType === 'VIDEO' ||
                    productType === 'REELS'
                ) {
                    imageUrl =
                        post.thumbnail_url ||
                        post.image_url ||
                        getCarouselImage(post) ||
                        post.media_url ||
                        null;
                } else {
                    imageUrl =
                        post.image_url ||
                        post.media_url ||
                        post.thumbnail_url ||
                        getCarouselImage(post) ||
                        null;
                }

                const permalink =
                    post.permalink ||
                    post.url ||
                    post.link ||
                    post.instagram_url ||
                    null;

                if (!imageUrl || !permalink) {
                    return null;
                }

                return {
                    id: post.id || null,
                    image_url: imageUrl,
                    permalink: permalink,

                    caption:
                        post.caption ||
                        post.text ||
                        post.description ||
                        '',

                    media_type:
                        post.media_type ||
                        post.type ||
                        '',

                    media_product_type:
                        post.media_product_type ||
                        '',

                    like_count:
                        post.like_count ??
                        post.likes_count ??
                        post.likes ??
                        null,

                    comments_count:
                        post.comments_count ??
                        post.comment_count ??
                        post.comments ??
                        null
                };
            }

            function instagramIcon() {
                return `
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect
                            x="2"
                            y="2"
                            width="20"
                            height="20"
                            rx="5"
                            ry="5">
                        </rect>

                        <path
                            d="M16 11.37A4 4 0 1 1 12.63 8
                               4 4 0 0 1 16 11.37z">
                        </path>

                        <line
                            x1="17.5"
                            y1="6.5"
                            x2="17.51"
                            y2="6.5">
                        </line>
                    </svg>
                `;
            }

            function carouselIcon() {
                return `
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect
                            x="6"
                            y="6"
                            width="14"
                            height="14"
                            rx="2">
                        </rect>

                        <path
                            d="M4 16V5a1 1 0 0 1 1-1h11">
                        </path>
                    </svg>
                `;
            }

            function videoIcon() {
                return `
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <polygon
                            points="5 3 19 12 5 21 5 3">
                        </polygon>
                    </svg>
                `;
            }

            function heartIcon() {
                return `
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0
                               L12 5.7l-1.1-1.1a5.5 5.5
                               0 0 0-7.8 7.8l1.1 1.1
                               L12 21.3l7.8-7.8a5.5
                               5.5 0 0 0 1-8.9z">
                        </path>
                    </svg>
                `;
            }

            function commentIcon() {
                return `
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M21 15a4 4 0 0 1-4 4H8
                               l-5 3V7a4 4 0 0 1 4-4h10
                               a4 4 0 0 1 4 4z">
                        </path>
                    </svg>
                `;
            }

            function getMediaIcon(post) {
                const mediaType = String(
                    post.media_type || ''
                ).toUpperCase();

                const productType = String(
                    post.media_product_type || ''
                ).toUpperCase();

                if (mediaType === 'CAROUSEL_ALBUM') {
                    return carouselIcon();
                }

                if (
                    mediaType === 'VIDEO' ||
                    productType === 'REELS'
                ) {
                    return videoIcon();
                }

                return instagramIcon();
            }

            function createEngagementItem(icon, count) {
                if (
                    count === undefined ||
                    count === null ||
                    count === ''
                ) {
                    return null;
                }

                const item = document.createElement('span');
                item.className =
                    'aces-instagram-engagement-item';
                item.innerHTML = icon;

                const countElement =
                    document.createElement('span');

                countElement.textContent =
                    formatNumber(count);

                item.appendChild(countElement);

                return item;
            }

            function createInstagramPost(post) {
                const link = document.createElement('a');

                link.className = 'aces-instagram-item';
                link.href = post.permalink;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';

                link.setAttribute(
                    'aria-label',
                    post.caption || 'View Instagram post'
                );

                const image = document.createElement('img');

                image.className = 'aces-instagram-image';
                image.src = post.image_url;

                image.alt =
                    post.caption ||
                    'Aces Lacrosse Instagram post';

                image.loading = 'lazy';
                image.decoding = 'async';

                image.addEventListener('error', function () {
                    link.remove();

                    if (
                        feedContainer.querySelectorAll(
                            '.aces-instagram-item'
                        ).length === 0
                    ) {
                        showMessage(
                            'Instagram post images could not be loaded.'
                        );
                    }
                });

                const mediaIcon =
                    document.createElement('span');

                mediaIcon.className =
                    'aces-instagram-media-icon';

                mediaIcon.innerHTML =
                    getMediaIcon(post);

                const overlay =
                    document.createElement('span');

                overlay.className =
                    'aces-instagram-overlay';

                const engagement =
                    document.createElement('span');

                engagement.className =
                    'aces-instagram-engagement';

                const likes = createEngagementItem(
                    heartIcon(),
                    post.like_count
                );

                const comments = createEngagementItem(
                    commentIcon(),
                    post.comments_count
                );

                if (likes) {
                    engagement.appendChild(likes);
                }

                if (comments) {
                    engagement.appendChild(comments);
                }

                const caption =
                    document.createElement('span');

                caption.className =
                    'aces-instagram-caption';

                caption.textContent =
                    post.caption ||
                    'View this post on Instagram';

                if (engagement.children.length > 0) {
                    overlay.appendChild(engagement);
                }

                overlay.appendChild(caption);

                link.appendChild(image);
                link.appendChild(mediaIcon);
                link.appendChild(overlay);

                return link;
            }

            async function parseResponse(response) {
                const responseText =
                    await response.text();

                if (!responseText.trim()) {
                    return {};
                }

                try {
                    return JSON.parse(responseText);
                } catch (error) {
                    throw new Error(
                        'The Instagram API returned invalid JSON.'
                    );
                }
            }

            async function loadInstagramFeed() {
                try {
                    const response = await fetch(feedUrl, {
                        method: 'GET',

                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        credentials: 'same-origin',
                        cache: 'no-store'
                    });

                    const result =
                        await parseResponse(response);

                    if (!response.ok) {
                        throw new Error(
                            result.message ||
                            result.error ||
                            (
                                'Instagram feed returned HTTP ' +
                                response.status
                            )
                        );
                    }

                    if (result.success === false) {
                        throw new Error(
                            result.message ||
                            'Instagram feed is unavailable.'
                        );
                    }

                    updateProfile(
                        extractProfile(result)
                    );

                    const posts = extractPosts(result)
                        .map(normalisePost)
                        .filter(Boolean)
                        .slice(0, 8);

                    feedContainer.innerHTML = '';

                    if (posts.length === 0) {
                        throw new Error(
                            result.message ||
                            'No Instagram posts are currently available.'
                        );
                    }

                    clearMessage();

                    posts.forEach(function (post) {
                        feedContainer.appendChild(
                            createInstagramPost(post)
                        );
                    });
                } catch (error) {
                    console.error(
                        'Aces Instagram feed error:',
                        error
                    );

                    showMessage(
                        error.message ||
                        'Instagram feed is temporarily unavailable.'
                    );
                }
            }

            loadInstagramFeed();
        }

        if (document.readyState === 'loading') {
            document.addEventListener(
                'DOMContentLoaded',
                initializeAcesInstagramFeed
            );
        } else {
            initializeAcesInstagramFeed();
        }
    })();
</script>

