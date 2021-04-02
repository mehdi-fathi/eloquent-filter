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
        $this->configurePaths();

        $this->mergeConfig();
    }

    /**
     * Configure package paths.
     */
    private function configurePaths()
    {
        $dir = str_replace('/tests', '', __DIR__);
        $this->publishes([
            $dir.'/src/config/config.php' => config_path('eloquentFilter.php'),
        ]);
    }

    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $dir = str_replace('/tests', '', __DIR__);

        $this->mergeConfigFrom(
            $dir.'/src/config/config.php',
            'eloquentFilter'
        );
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
}

//php artisan vendor:publish --provider="eloquentFilter\QueryFilter\ServiceProvider"
