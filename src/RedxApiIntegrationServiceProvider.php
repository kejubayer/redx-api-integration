<?php

namespace Kejubayer\RedxApiIntegration;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RedxApiIntegrationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/redx-api-integration.php', 'redx-api-integration');

        $this->app->singleton(RedxApiIntegration::class, function (Application $app): RedxApiIntegration {
            return new RedxApiIntegration($app['http'], $app['config']->get('redx-api-integration', []));
        });

        $this->app->alias(RedxApiIntegration::class, 'redx');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/webhook.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/redx-api-integration.php' => config_path('redx-api-integration.php'),
            ], 'redx-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/2026_01_01_000000_create_redx_webhook_requests_table.php' => database_path('migrations/2026_01_01_000000_create_redx_webhook_requests_table.php'),
            ], 'redx-migrations');
        }
    }
}
