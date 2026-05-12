<?php

use Illuminate\Support\Facades\Route;
use Kejubayer\RedxApiIntegration\Http\Controllers\RedxWebhookController;

if (config('redx-api-integration.webhook.enabled', true)) {
    Route::post(config('redx-api-integration.webhook.path', 'redx/webhook'), RedxWebhookController::class)
        ->middleware(config('redx-api-integration.webhook.middleware', ['api']))
        ->name(config('redx-api-integration.webhook.route_name', 'redx.webhook'));
}
