<?php
namespace eloquentFilter\QueryFilter;

use Illuminate\Support\ServiceProvider;

class filterServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind('jalali', function ($app) {
//            return new jDate;
//        });
//
//        $this->app->bind('jDateTime', function ($app) {
//            return new jDateTime;
//        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('jalali', 'jDateTime');
    }

}
