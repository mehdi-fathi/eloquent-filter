<?php

namespace eloquentFilter;

use eloquentFilter\Command\MakeEloquentFilter;
use eloquentFilter\Facade\EloquentFilter;
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
        \Illuminate\Database\Query\Builder::macro('filter', function ($request) {

            app()->singleton(
                'eloquentFilter',
                function () {

                    $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

                    $request = app(RequestFilter::class, ['request' => request()->query()]);

                    $core = $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder();

                    $response = app(ResponseFilter::class);

                    return app(QueryFilterBuilder::class, [
                        'queryFilterCore' => $core,
                        'requestFilter' => $request,
                        'responseFilter' => $response
                    ]);
                }
            );

            return EloquentFilter::apply(
                builder: $this,
                request: $request,
            );
        });

        $this->app->singleton(
            'eloquentFilter',
            function () {
                $queryFilterCoreFactory = new QueryFilterCoreFactory();

                $request = new RequestFilter($this->app->get('request')->query());
                $response = new ResponseFilter();

                $core = $queryFilterCoreFactory->createQueryFilterCoreEloquentBuilder();

                return new QueryFilterBuilder(
                    queryFilterCore: $core,
                    requestFilter: $request,
                    responseFilter: $response
                );
            }
        );

        $this->commands([MakeEloquentFilter::class]);
    }
}
