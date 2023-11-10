<?php

use eloquentFilter\Facade\EloquentFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryFilterBuilder;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->request = m::mock(\Illuminate\Http\Request::class);
        $this->config = require __DIR__ . '/../src/config/config.php';

        $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

        $createEloquentFilter = function ($requestData, $queryFilterCore) {
            $requestFilter = app(RequestFilter::class, ['request' => $requestData]);
            $responseFilter = app(ResponseFilter::class);

            return app(QueryFilterBuilder::class, [
                'queryFilterCore' => $queryFilterCore,
                'requestFilter' => $requestFilter,
                'responseFilter' => $responseFilter
            ]);
        };

        \Illuminate\Database\Query\Builder::macro('filter', function ($request) use ($createEloquentFilter, $queryFilterCoreFactory) {
            app()->singleton('eloquentFilter', function () use ($createEloquentFilter, $queryFilterCoreFactory) {
                return $createEloquentFilter(request()->query(), $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder());
            });

            return EloquentFilter::apply(builder: $this, request: $request);
        });

        $this->app->singleton('eloquentFilter', function () use ($createEloquentFilter, $queryFilterCoreFactory) {

            return $createEloquentFilter($this->request->query(), $queryFilterCoreFactory->createQueryFilterCoreEloquentBuilder());
        });
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
