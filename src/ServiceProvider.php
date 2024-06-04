<?php

namespace eloquentFilter;

use eloquentFilter\Command\MakeEloquentFilter;
use eloquentFilter\Facade\EloquentFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryFilterBuilder;
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

        $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

        $createEloquentFilter = function ($requestData, QueryFilterCore $queryFilterCore, $call_back = null) {
            $requestFilter = app(RequestFilter::class, ['request' => $requestData]);
            $responseFilter = app(ResponseFilter::class);

            return app(QueryFilterBuilder::class, [
                'queryFilterCore' => $queryFilterCore,
                'requestFilter' => $requestFilter,
                'responseFilter' => $responseFilter,
                'call_back' => $call_back,
            ]);
        };

        \Illuminate\Database\Query\Builder::macro('filter', function ($request = null, $call_back = null) use ($createEloquentFilter, $queryFilterCoreFactory) {

            if (empty($request)) {
                $request = request()->query();
            }

            app()->singleton(
                'eloquentFilter',
                function () use ($createEloquentFilter, $queryFilterCoreFactory, $request) {
                    /* @see QueryFilterCoreFactory::createQueryFilterCoreDBQueryBuilder */
                    return $createEloquentFilter($request, $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder());
                }
            );

            return app('eloquentFilter')->apply(builder: $this, request: $request);
        });

        \Illuminate\Database\Query\Builder::macro('getResponseFilter', function ($callback = null) use ($createEloquentFilter, $queryFilterCoreFactory) {

            if(!empty($callback)){
                return call_user_func($callback, EloquentFilter::getResponse());
            }

        });

        $this->app->singleton(
            'eloquentFilter',
            function () use ($createEloquentFilter, $queryFilterCoreFactory) {
                /* @see QueryFilterCoreFactory::createQueryFilterCoreEloquentBuilder */
                return $createEloquentFilter($this->app->get('request')->query(), $queryFilterCoreFactory->createQueryFilterCoreEloquentBuilder());
            }
        );

        $this->commands([MakeEloquentFilter::class]);
    }
}
