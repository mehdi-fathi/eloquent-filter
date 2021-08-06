<?php

use eloquentFilter\QueryFilter\Core\QueryFilterWrapper;
use Mockery as m;

/**
 * Class TestCase.
 */
class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @var m\LegacyMockInterface|m\MockInterface
     */
    public $request;

    /**
     * @var array
     */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->withFactories(__DIR__.'/database/factories');

        $this->request = m::mock(\Illuminate\Http\Request::class);

        $this->config = require '/Users/mehdi/Sites/eloquent-filter/src/config/config.php';

        $this->app->singleton(
            'eloquentFilter',
            function () {
                return new QueryFilterWrapper($this->request->query());
            }
        );
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\eloquentFilter\ServiceProviderTest::class];
    }
}
