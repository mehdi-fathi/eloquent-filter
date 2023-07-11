<?php

use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryFilterBuilder;
use eloquentFilter\QueryFilter\Core\FilterBuilder\ResponseFilter;
use eloquentFilter\QueryFilter\Factory\QueryFilterCoreFactory;
use Mockery as m;
use eloquentFilter\QueryFilter\Core\FilterBuilder\RequestFilter;

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

        global $argv;

        $driver = !empty($argv[2]) ? $argv[2] : 'eloquent';

        $this->request = m::mock(\Illuminate\Http\Request::class);

        $this->config = require __DIR__ . '/../src/config/config.php';

        $this->app->singleton(
            'eloquentFilter',
            function () use($driver) {

                $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

                $request = app(RequestFilter::class, ['request' => $this->request->query()]);

                //vendor/bin/phpunit tests/. db -- command for runnign test on db
                if($driver == 'db'){
                    $core = $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder();

                }else{
                    $core = $queryFilterCoreFactory->createQueryFilterCoreEloquentBuilder();
                }

                $response = app(ResponseFilter::class);

                return app(QueryFilterBuilder::class, [
                    'queryFilterCore' => $core,
                    'requestFilter' => $request,
                    'responseFilter' => $response
                ]);
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
