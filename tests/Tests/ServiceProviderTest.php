<?php

namespace eloquentFilter;

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
                return new QueryFilter($this->request->all());
            }
        );
    }
}
