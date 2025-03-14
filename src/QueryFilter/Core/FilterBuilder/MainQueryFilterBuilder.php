<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryBuilder\DBQueryFilterBuilder;
use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryBuilder\EloquentQueryFilterBuilder;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Core\RateLimiting;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;

/**
 * Class MainQueryFilterBuilder.
 */
class MainQueryFilterBuilder
{
    use HelperEloquentFilter;
    use RateLimiting;

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
     * @throws \ReflectionException
     */
    public function apply($builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detections_injected = null, array $black_list_detections = null)
    {
        // Check rate limit before applying filter
        $this->checkRateLimit();

        if (!empty($request)) {
            $this->requestFilter->setPureRequest($request);
        }

        if ($this->checkEnableEloquentFilter()) {
            return $builder;
        }

        return $this->buildQuery(
            builder: $builder,
            ignore_request: $ignore_request,
            accept_request: $accept_request,
            detections_injected: $detections_injected,
            black_list_detections: $black_list_detections
        );

    }


    /**
     * @return string
     */
    public function getNameBuilder(): string
    {
        $MainBuilderConditions = $this->queryFilterCore->getMainBuilderConditions();

        return $MainBuilderConditions->getName();
    }

    /**
     * @return bool
     */
    private function checkEnableEloquentFilter(): bool
    {
        return !config('eloquentFilter.enabled') || empty($this->requestFilter->getRequest());
    }

    /**
     * @param $builder
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     * @return mixed|null
     * @throws \ReflectionException
     */
    private function buildQuery($builder, ?array $ignore_request, ?array $accept_request, ?array $detections_injected, ?array $black_list_detections): mixed
    {
        if ($this->isDbBuilder()) {

            return $this->buildDbQuery($builder, $ignore_request, $accept_request, $detections_injected, $black_list_detections);
        }

        return $this->buildEloquentQuery($builder, $ignore_request, $accept_request, $detections_injected, $black_list_detections);
    }

    /**
     * @param $builder
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     * @return null
     * @throws \ReflectionException
     */
    private function buildDbQuery($builder, ?array $ignore_request, ?array $accept_request, ?array $detections_injected, ?array $black_list_detections): mixed
    {

        $this->requestFilter->handleRequestDb(
            ignore_request: $ignore_request,
            accept_request: $accept_request
        );

        $DBQueryFilterBuilder = new DBQueryFilterBuilder($this->queryFilterCore, $this->requestFilter, $this->responseFilter);

        return $DBQueryFilterBuilder->apply(
            builder: $builder,
            detections_injected: $detections_injected,
            black_list_detections: $black_list_detections
        );
    }

    /**
     * @param $builder
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     * @return mixed
     * @throws \ReflectionException
     */
    private function buildEloquentQuery($builder, ?array $ignore_request, ?array $accept_request, ?array $detections_injected, ?array $black_list_detections): mixed
    {
        $this->requestFilter->handleRequest(
            builder: $builder,
            ignore_request: $ignore_request,
            accept_request: $accept_request
        );

        $eloquentQueryFilterBuilder = new EloquentQueryFilterBuilder($this->queryFilterCore, $this->requestFilter, $this->responseFilter);

        return $eloquentQueryFilterBuilder->apply(
            builder: $builder,
            detections_injected: $detections_injected,
            black_list_detections: $black_list_detections
        );
    }

    /**
     * @return bool
     */
    private function isDbBuilder(): bool
    {
        return $this->getNameBuilder() == DBBuilderQueryByCondition::NAME;
    }

}
