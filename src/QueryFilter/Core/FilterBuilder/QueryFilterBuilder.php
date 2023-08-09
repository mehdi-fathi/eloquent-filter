<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;

/**
 * Class QueryFilterBuilder.
 */
class QueryFilterBuilder
{
    use HelperEloquentFilter;

    /**
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore $queryFilterCore
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter $requestFilter
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter $responseFilter
     */
    public function __construct(public QueryFilterCore $queryFilterCore, public RequestFilter $requestFilter, public ResponseFilter $responseFilter)
    {
    }

    /**
     * @param \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper $queryBuilderWrapper
     */
    public function setQueryBuilderWrapper(QueryBuilderWrapper $queryBuilderWrapper): void
    {
        $this->queryBuilderWrapper = $queryBuilderWrapper;
    }

    /**
     * @return \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper
     */
    public function getQueryBuilderWrapper(): QueryBuilderWrapper
    {
        return $this->queryBuilderWrapper;
    }

    /**
     * @param $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     *
     * @return void
     */
    public function apply($builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detections_injected = null, array $black_list_detections = null)
    {
        $this->buildExclusiveMacros($detections_injected);

        $this->setQueryBuilderWrapper(QueryBuilderWrapperFactory::createQueryBuilder($builder));

        if (!empty($request)) {
            $this->requestFilter->setPureRequest($request);
        }

        if (!config('eloquentFilter.enabled') || empty($this->requestFilter->getRequest())) {
            return $builder;
        }

        if ($this->getNameDriver() == 'DbBuilder') {

            $db = new DBQueryFilterBuilder($this->queryFilterCore, $this->requestFilter, $this->responseFilter);

            return $db->apply($builder, $request, $ignore_request, $accept_request, $detections_injected, $black_list_detections);
        }

        $db = new EloquentQueryFilterBuilder($this->queryFilterCore, $this->requestFilter, $this->responseFilter);

        return $db->apply($builder, $request, $ignore_request, $accept_request, $detections_injected, $black_list_detections);

    }

    /**
     * @param array|null $detections_injected
     * @return void
     */
    private function buildExclusiveMacros(?array $detections_injected): void
    {
        \Illuminate\Database\Eloquent\Builder::macro('isUsedEloquentFilter', function () {
            return config('eloquentFilter.enabled');
        });

        \Illuminate\Database\Eloquent\Builder::macro('getDetectionsInjected', function () use ($detections_injected) {
            return $detections_injected;
        });

    }


    /**
     * @return string
     */
    public function getNameDriver()
    {
        $MainBuilderConditions = $this->queryFilterCore->getMainBuilderConditions();

        return $MainBuilderConditions->getName();
    }

}
