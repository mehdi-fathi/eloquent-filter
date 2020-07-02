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
