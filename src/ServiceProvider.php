<?php

namespace eloquentFilter;

use eloquentFilter\Command\MakeEloquentFilter;
use eloquentFilter\Facade\EloquentFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\MainQueryFilterBuilder;
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
        $this->configurePaths();

        $this->mergeConfig();

        $this->registerBindings();
    }

    /**
     * Configure package paths.
     */
    private function configurePaths()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('eloquentFilter.php'),
        ]);
    }

    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php',
            'eloquentFilter'
        );
    }

    /**
     *
     */
    private function registerBindings()
    {
        /* @var $queryFilterCoreFactory QueryFilterCoreFactory */
        $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

        $createQueryFilterBuilder = function ($requestData, QueryFilterCore $queryFilterCore) {
            $requestFilter = app(RequestFilter::class, ['request' => $requestData]);
            $responseFilter = app(ResponseFilter::class);

            return app(MainQueryFilterBuilder::class, [
                'queryFilterCore' => $queryFilterCore,
                'requestFilter' => $requestFilter,
                'responseFilter' => $responseFilter,
            ]);
        };

        \Illuminate\Database\Query\Builder::macro('filter', function ($request = null) use ($createQueryFilterBuilder, $queryFilterCoreFactory) {

            if (empty($request)) {
                $request = request()->query();
            }

            app()->singleton(
                'eloquentFilter',
                function () use ($createQueryFilterBuilder, $queryFilterCoreFactory, $request) {
                    return $createQueryFilterBuilder($request, $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder());
                }
            );

            /** @see MainQueryFilterBuilder::apply() */
            return app('eloquentFilter')->apply(builder: $this, request: $request);
        });

        \Illuminate\Database\Query\Builder::macro('getResponseFilter', function ($callback = null) use ($createQueryFilterBuilder, $queryFilterCoreFactory) {

            if(!empty($callback)){
                return call_user_func($callback, EloquentFilter::getResponse());
            }

        });

        $this->app->singleton(
            'eloquentFilter',
            function () use ($createQueryFilterBuilder, $queryFilterCoreFactory) {

                /* @see MainQueryFilterBuilder */
                return $createQueryFilterBuilder($this->app->get('request')->query(), $queryFilterCoreFactory->createQueryFilterCoreEloquentBuilder());
            }
        );

        $this->commands([MakeEloquentFilter::class]);
    }
}
