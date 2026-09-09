<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'instagram' => [
        'name' => env(
            'INSTAGRAM_NAME',
            'Aces Lacrosse'
        ),

        'username' => env(
            'INSTAGRAM_USERNAME',
            'aceslacrosse_'
        ),

        'user_id' => env(
            'INSTAGRAM_USER_ID',
            'me'
        ),

        'access_token' => env(
            'INSTAGRAM_ACCESS_TOKEN'
        ),

        'base_url' => env(
            'INSTAGRAM_GRAPH_BASE_URL',
            'https://graph.instagram.com'
        ),

        'version' => env(
            'INSTAGRAM_GRAPH_VERSION',
            'v22.0'
        ),

        'post_limit' => env(
            'INSTAGRAM_POST_LIMIT',
            8
        ),

        'cache_minutes' => env(
            'INSTAGRAM_CACHE_MINUTES',
            30
        ),
    ],

];
