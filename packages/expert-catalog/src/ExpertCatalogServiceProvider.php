<?php

namespace ExpertCatalog;

use Illuminate\Support\ServiceProvider;

class ExpertCatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/expert-catalog.php', 'expert-catalog');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/expert-catalog.php' => config_path('expert-catalog.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'expert-catalog');
    }
}
