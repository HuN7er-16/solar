<?php

namespace BehinCrmContractors;

use Illuminate\Support\ServiceProvider;

class CrmContractorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'CrmContractorsView');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \BehinCrmContractors\Commands\SyncContractorsCommand::class,
            ]);
        }
    }
}
