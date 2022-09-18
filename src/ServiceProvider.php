<?php

namespace eloquentFilter;

use eloquentFilter\Command\MakeEloquentFilter;
use eloquentFilter\QueryFilter\Core\QueryFilterCoreBuilder;
use eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
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

    /**
     *
     */
    private function registerBindings()
    {
        $this->app->singleton(
            'eloquentFilter',
            function () {

                $queryFilterCoreFactory = new QueryFilterCoreFactory();

                $core = $queryFilterCoreFactory->createQueryFilterCoreBuilder($this->app->get('request')->query());

                return new QueryFilterCoreBuilder($core);
            }
        );

        $this->commands([MakeEloquentFilter::class]);
    }
}
