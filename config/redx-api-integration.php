<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RedX API
    |--------------------------------------------------------------------------
    |
    | Keep these values in the host application's .env file. The package uses
    | a configurable endpoint map because RedX environments and API versions can
    | differ between merchant accounts.
    |
    */

    'base_url' => env('REDX_BASE_URL', 'https://openapi.redx.com.bd/v1.0.0-beta'),

    'api_token' => env('REDX_API_TOKEN'),

    'token_header' => env('REDX_TOKEN_HEADER', 'API-ACCESS-TOKEN'),

    'timeout' => env('REDX_TIMEOUT', 30),

    'endpoints' => [
        'create_parcel' => env('REDX_CREATE_PARCEL_ENDPOINT', '/parcel'),
        'parcel_details' => env('REDX_PARCEL_DETAILS_ENDPOINT', '/parcel/{parcel_id}'),
        'track_parcel' => env('REDX_TRACK_PARCEL_ENDPOINT', '/parcel/track/{tracking_id}'),
        'cancel_parcel' => env('REDX_CANCEL_PARCEL_ENDPOINT', '/parcel/{parcel_id}/cancel'),
        'areas' => env('REDX_AREAS_ENDPOINT', '/areas'),
        'stores' => env('REDX_STORES_ENDPOINT', '/stores'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook
    |--------------------------------------------------------------------------
    */

    'webhook' => [
        'enabled' => env('REDX_WEBHOOK_ENABLED', true),
        'path' => env('REDX_WEBHOOK_PATH', 'redx/webhook'),
        'middleware' => ['api'],
        'route_name' => 'redx.webhook',

        /*
        | Optional shared-secret check. When REDX_WEBHOOK_SECRET is set, inbound
        | requests must contain the same value in this header.
        */
        'secret' => env('REDX_WEBHOOK_SECRET'),
        'secret_header' => env('REDX_WEBHOOK_SECRET_HEADER', 'X-Redx-Webhook-Secret'),
    ],

    'webhook_model' => Kejubayer\RedxApiIntegration\Models\RedxWebhookRequest::class,
];
