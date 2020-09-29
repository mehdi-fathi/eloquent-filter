<?php

use eloquentFilter\QueryFilter\QueryFilter;
use Mockery as m;

/**
 * Class TestCase.
 */
class TestCase extends Orchestra\Testbench\TestCase
{
    public $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->withFactories(__DIR__.'/database/factories');

        $this->request = m::mock(\Illuminate\Http\Request::class);

        $this->app->singleton(
            'eloquentFilter',
            function () {
                return new QueryFilter($this->request->query());
            }
        );
    }

    /**
     * Get application providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            // your package service provider,
            Orchestra\Database\ConsoleServiceProvider::class,
            \eloquentFilter\ServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {

//        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
//            // Ignores notices and reports all other kinds... and warnings
//            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//            // error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
//        }
//
//        $config = require 'config/database.php';
//
//        $app['config']->set('app.key', 'ZsZewWyUJ5FsKp9lMwv4tYbNlegQilM7');
//
//        $app['config']->set('database.default', 'mysql');
//        $app['config']->set('database.connections.mysql', $config['connections']['mysql']);
//
//        $app['config']->set('auth.model', 'User');
//        $app['config']->set('auth.providers.users.model', 'User');
//        $app['config']->set('cache.driver', 'array');
//
//        $app['config']->set('queue.default', 'database');
//        $app['config']->set('queue.connections.database', [
//            'driver' => 'mysql',
//            'table'  => 'jobs',
//            'queue'  => 'default',
//            'expire' => 60,
//        ]);
//
//        $host = Config::get('database.connections.mysql.host');
//        $database = 'eloquentFilter_test';
//        $username = Config::get('database.connections.mysql.username');
//        $password = Config::get('database.connections.mysql.password');
//        echo shell_exec('mysql -h '.$host.' -u '.$username.' -p'.
//        $password.' -e "CREATE DATABASE IF NOT EXISTS '.$database.'"');
//
//        $app['config']->set('database.connections.mysql.database', $database);
    }
}
