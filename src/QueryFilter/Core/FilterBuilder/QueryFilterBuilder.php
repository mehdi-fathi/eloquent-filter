<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;

/**
 * Class QueryFilterBuilder.
 */
class QueryFilterBuilder
{
    use HelperEloquentFilter;

    /**
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore $queryFilterCore
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter $requestFilter
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter $responseFilter
     */
    public function __construct(public QueryFilterCore $queryFilterCore, public RequestFilter $requestFilter, public ResponseFilter $responseFilter)
    {
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

        if (!empty($request)) {
            $this->requestFilter->setPureRequest($request);
        }

        if (!config('eloquentFilter.enabled') || empty($this->requestFilter->getRequest())) {
            return $builder;
        }

        if ($this->getNameDriver() == DBBuilderQueryByCondition::NAME) {

            $db = new DBQueryFilterBuilder($this->queryFilterCore, $this->requestFilter, $this->responseFilter);

            return $db->apply($builder, $ignore_request, $accept_request, $detections_injected, $black_list_detections);
        }

        $db = new EloquentQueryFilterBuilder($this->queryFilterCore, $this->requestFilter, $this->responseFilter);

        return $db->apply($builder, $ignore_request, $accept_request, $detections_injected, $black_list_detections);

    }

    /**
     * @param array|null $detections_injected
     * @return void
     */
    private function buildExclusiveMacros(?array $detections_injected): void
    {
        //todo why it's limited to just Eloquent\Builder. We should consider this on db
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
    public function getNameDriver(): string
    {
        $MainBuilderConditions = $this->queryFilterCore->getMainBuilderConditions();

        return $MainBuilderConditions->getName();
    }

}
