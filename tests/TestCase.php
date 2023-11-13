<?php

use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
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

        $queryFilterCoreFactory = app(QueryFilterCoreFactory::class);

        $createEloquentFilter = function ($requestData, QueryFilterCore $queryFilterCore) {
            $requestFilter = app(RequestFilter::class, ['request' => $requestData]);
            $responseFilter = app(ResponseFilter::class);

            return app(QueryFilterBuilder::class, [
                'queryFilterCore' => $queryFilterCore,
                'requestFilter' => $requestFilter,
                'responseFilter' => $responseFilter
            ]);
        };

        $reqQueryStr = $this->request;

        \Illuminate\Database\Query\Builder::macro('filter', function ($request = null) use ($createEloquentFilter, $queryFilterCoreFactory, $reqQueryStr) {

            if (empty($request)) {
                $request = $reqQueryStr->query();
            }

            app()->singleton('eloquentFilter', function () use ($createEloquentFilter, $queryFilterCoreFactory, $request) {
                return $createEloquentFilter($request, $queryFilterCoreFactory->createQueryFilterCoreDBQueryBuilder());
            });

            return app('eloquentFilter')->apply(builder: $this, request: $request);
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
