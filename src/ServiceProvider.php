<?php

namespace eloquentFilter;

use eloquentFilter\QueryFilter\QueryFilter;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->configurePaths();

        $this->mergeConfig();
    }

    /**
     * Configure package paths.
     */
    private function configurePaths()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('eloquentFilter.php'),
        ]);
    }

    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'eloquentFilter'
        );
    }

    private function registerBindings()
    {
        $this->app->singleton(
            'eloquentFilter',
            function () {
                return new QueryFilter($this->app->get('request')->query());
            }
        );
    }
}
