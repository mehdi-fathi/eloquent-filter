<?php

namespace eloquentFilter;

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProviderTest extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->registerBindings();
//
//        $this->registerConsole();
    }

    protected function bootPublishes(): void
    {
    }

    /**
     * @return string
     */
    protected function configPath(): string
    {
    }

    private function registerBindings()
    {
        $this->app->singleton(
            'eloquentFilter',
            function () {
                return new ModelFilters($this->request->all());
            }
        );
    }
}
