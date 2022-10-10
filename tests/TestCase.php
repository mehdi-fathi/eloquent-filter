<?php

use eloquentFilter\QueryFilter\Core\QueryFilterBuilder;
use eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory;
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

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->withFactories(__DIR__ . '/database/factories');

        $this->request = m::mock(\Illuminate\Http\Request::class);

        $this->config = require __DIR__ . '/../src/config/config.php';

        if (config('eloquentFilter.enabled') != false) {

            $this->app->singleton(
                'eloquentFilter',
                function () {

                    $queryFilterCoreFactory = new QueryFilterCoreFactory();

                    $request = new \eloquentFilter\QueryFilter\Core\RequestFilter($this->request->query());

                    $core = $queryFilterCoreFactory->createQueryFilterCoreBuilder();

                    return new QueryFilterBuilder($core, $request);
                }
            );
        }
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
