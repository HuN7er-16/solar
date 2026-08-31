<?php

namespace ExpertInitialVisit;

use Illuminate\Support\ServiceProvider;

class ExpertInitialVisitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/expert-initial-visit.php', 'expert-initial-visit');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/expert-initial-visit.php' => config_path('expert-initial-visit.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'expert-initial-visit');
    }
}
