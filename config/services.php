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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_SES_USER'),
        'secret' => env('AWS_SES_PASS'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'shopify' => [
        'client_id' => env('SHOPIFY_API_ID'),
        'client_secret' => env('SHOPIFY_API_SECRET'),
        'redirect' => env('SHOPIFY_REDIRECT_URI'),
        'script_tag' => env('SHOPIFY_SCRIPT_TAG', 'https://app.simplepost.co/js/collector.js'),
        'api_version' => env('SHOPIFY_API_VERSION','2021-10')
    ],
    'design_huddle' => [
        'client_id' => env('DESIGN_HUDDLE_CLIENT_ID'),
        'client_secret' => env('DESIGN_HUDDLE_CLIENT_SECRET'),
        'api_url' => env('DESIGN_HUDDLE_API_URL')
    ],

];
