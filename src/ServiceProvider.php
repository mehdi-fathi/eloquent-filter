<?php

namespace eloquentFilter;

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
                return new ModelFilters($this->app->get('request')->query());
            }
        );
    }
}
