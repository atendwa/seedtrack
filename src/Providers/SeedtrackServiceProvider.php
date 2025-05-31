<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack\Providers;

use Atendwa\Seedtrack\Console\Commands\DiscoverSeeders;
use Atendwa\Seedtrack\Seedtrack;
use Illuminate\Support\ServiceProvider;

class SeedtrackServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('Seedtrack', fn (): Seedtrack => new Seedtrack);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands(DiscoverSeeders::class);

            $this->publishes(
                [__DIR__ . '/../../database/migrations' => database_path('migrations')], 'migrations'
            );
        }
    }
}
