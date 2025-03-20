<?php

namespace eloquentFilter;

use eloquentFilter\Command\MakeEloquentFilter;
use eloquentFilter\Facade\EloquentFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\MainQueryFilterBuilder;
use eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory;
use Illuminate\Cache\RateLimiter;
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

        // Register RateLimiter singleton
        $this->app->singleton('eloquent.filter.limiter', function ($app) {
            return new RateLimiter($app['cache.store']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app['config']->get('eloquentFilter.rate_limit.enabled', true)) {
            $this->app['router']->aliasMiddleware('filter.throttle', FilterRateLimiter::class);
        }
    }

    /**
     * Configure package paths.
     */
    private function configurePaths(): void
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
    private function registerBindings(): void
    {
        $this->registerAllDependencies();

        $this->commands([MakeEloquentFilter::class]);
    }

    /**
     * @return void
     */
    private function registerAllDependencies(): void
    {
        /* @var $queryFilterCoreFactory QueryFilterCoreFactory */
        $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

        $mainQueryFilterBuilder = $this->setMainQueryFilterBuilder();

        $this->attachFilterToQueryBuilder($mainQueryFilterBuilder, $queryFilterCoreFactory);

        $this->attachResponseFilterToQueryBuilder($mainQueryFilterBuilder, $queryFilterCoreFactory);

        $this->setEloquentFilter($mainQueryFilterBuilder, $queryFilterCoreFactory);
    }

    /**
     * @param \Closure $mainQueryFilterBuilder
     * @param \eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory $queryFilterCoreFactory
     * @return void
     */
    private function attachFilterToQueryBuilder(\Closure $mainQueryFilterBuilder, QueryFilterCoreFactory $queryFilterCoreFactory): void
    {
        \Illuminate\Database\Query\Builder::macro('filter', function ($request = null) use ($mainQueryFilterBuilder, $queryFilterCoreFactory) {

            if (empty($request)) {
                $request = request()->query();
            }

            app()->singleton(
                'eloquentFilter',
                function () use ($mainQueryFilterBuilder, $queryFilterCoreFactory, $request) {
                    return $mainQueryFilterBuilder($request, $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder());
                }
            );

            /** @see MainQueryFilterBuilder::apply() */
            return app('eloquentFilter')->apply(builder: $this, request: $request);
        });
    }

    /**
     * @param \Closure $mainQueryFilterBuilder
     * @param \eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory $queryFilterCoreFactory
     * @return void
     */
    private function attachResponseFilterToQueryBuilder(\Closure $mainQueryFilterBuilder, QueryFilterCoreFactory $queryFilterCoreFactory): void
    {
        \Illuminate\Database\Query\Builder::macro('getResponseFilter', function ($callback = null) use ($mainQueryFilterBuilder, $queryFilterCoreFactory) {

            if (!empty($callback)) {
                return call_user_func($callback, EloquentFilter::getResponse());
            }

        });
    }

    /**
     * @param \Closure $mainQueryFilterBuilder
     * @param \eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory $queryFilterCoreFactory
     * @return void
     */
    private function setEloquentFilter(\Closure $mainQueryFilterBuilder, QueryFilterCoreFactory $queryFilterCoreFactory): void
    {
        $this->app->singleton(
            'eloquentFilter',
            function () use ($mainQueryFilterBuilder, $queryFilterCoreFactory) {

                /* @see MainQueryFilterBuilder */
                return $mainQueryFilterBuilder($this->app->get('request')->query(), $queryFilterCoreFactory->createQueryFilterCoreEloquentBuilder());
            }
        );
    }

    /**
     * @return \Closure
     */
    private function setMainQueryFilterBuilder(): \Closure
    {
        $mainQueryFilterBuilder = function ($requestData, QueryFilterCore $queryFilterCore) {
            $requestFilter = app(RequestFilter::class, ['request' => $requestData]);
            $responseFilter = app(ResponseFilter::class);

            return app(MainQueryFilterBuilder::class, [
                'queryFilterCore' => $queryFilterCore,
                'requestFilter' => $requestFilter,
                'responseFilter' => $responseFilter,
            ]);
        };
        return $mainQueryFilterBuilder;
    }
}
