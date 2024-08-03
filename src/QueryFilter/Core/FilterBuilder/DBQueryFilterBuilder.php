<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface;
use eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Core\ResolverDetections;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;

/**
 * Class DBQueryFilterBuilder.
 */
class DBQueryFilterBuilder
{
    use HelperEloquentFilter;

    protected DbBuilderWrapperInterface $queryBuilderWrapper;

    /**
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore $queryFilterCore
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter $requestFilter
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter $responseFilter
     */
    public function __construct(public QueryFilterCore $queryFilterCore, public RequestFilter $requestFilter, public ResponseFilter $responseFilter)
    {
    }

    /**
     * @param \eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface $queryBuilderWrapper
     */
    public function setQueryBuilderWrapper(DbBuilderWrapperInterface $queryBuilderWrapper): void
    {
        $this->queryBuilderWrapper = $queryBuilderWrapper;
    }

    /**
     * @return \eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface
     */
    public function getQueryBuilderWrapper(): DbBuilderWrapperInterface
    {
        return $this->queryBuilderWrapper;
    }

    /**
     * @param $builder
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     *
     * @return void
     */
    public function apply($builder, array $ignore_request = null, array $accept_request = null, array $detections_injected = null, array $black_list_detections = null)
    {
        $this->setQueryBuilderWrapper(QueryBuilderWrapperFactory::createDbQueryBuilder($builder));

        $this->handleRequest(
            ignore_request: $ignore_request,
            accept_request: $accept_request
        );

        $this->resolveDetections($detections_injected, $black_list_detections);

        return $this->responseFilter->getResponse();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function resolveDetections($detections_injected, $black_list_detections)
    {
        $this->queryFilterCore->unsetDetection($black_list_detections);
        $this->queryFilterCore->setDetectionsDbInjected($detections_injected);

        /** @see ResolverDetections */
        app()->bind('ResolverDetections', function () {
            return new ResolverDetections(
                builder: $this->getQueryBuilderWrapper()->getBuilder(),
                request: $this->requestFilter->getRequest(),
                detector_factory: $this->queryFilterCore->getDetectFactory(),
                main_builder_conditions_contract: $this->queryFilterCore->getMainBuilderConditions()
            );
        });

        /** @see ResolverDetections::getResolverOut() */
        $responseResolver = app('ResolverDetections')->getResolverOut();

        $this->responseFilter->setResponse($responseResolver);
    }

    /**
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @return void
     */
    private function handleRequest(?array $ignore_request, ?array $accept_request): void
    {

        $serialize_request_filter = $this->requestFilter->getRequest();

        $this->requestFilter->requestAlter(
            ignore_request: $ignore_request,
            accept_request: $accept_request,
            serialize_request_filter: $serialize_request_filter,
            alias_list_filter: $alias_list_filter ?? [],
            model: null,
        );
    }

}
