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
        'track_parcel' => env('REDX_TRACK_PARCEL_ENDPOINT', '/parcel/track/{tracking_id}'),
        'parcel_details' => env('REDX_PARCEL_DETAILS_ENDPOINT', '/parcel/info/{tracking_id}'),
        'update_parcel' => env('REDX_UPDATE_PARCEL_ENDPOINT', '/parcels'),
        'areas' => env('REDX_AREAS_ENDPOINT', '/areas'),
        'create_pickup_store' => env('REDX_CREATE_PICKUP_STORE_ENDPOINT', '/pickup/store'),
        'pickup_stores' => env('REDX_PICKUP_STORES_ENDPOINT', '/pickup/stores'),
        'pickup_store_details' => env('REDX_PICKUP_STORE_DETAILS_ENDPOINT', '/pickup/store/info/{pickup_store_id}'),
        'charge_calculator' => env('REDX_CHARGE_CALCULATOR_ENDPOINT', '/charge/charge_calculator'),
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
