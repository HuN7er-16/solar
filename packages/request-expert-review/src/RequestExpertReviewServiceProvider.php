<?php

namespace RequestExpertReview;

use Illuminate\Support\ServiceProvider;

class RequestExpertReviewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/request-expert-review.php', 'request-expert-review');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/request-expert-review.php' => config_path('request-expert-review.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'request-expert-review');
    }
}
