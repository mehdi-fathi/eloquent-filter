<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Core\ResolverDetections;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;

/**
 * Class QueryFilterBuilder.
 */
class QueryFilterBuilder
{
    use HelperEloquentFilter;

    /**
     * @var \eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCoreBuilder
     */

    public QueryFilterCore $queryFilterCore;

    /**
     * @var \eloquentFilter\QueryFilter\Core\FilterBuilder\RequestFilter
     */

    public RequestFilter $requestFilter;

    /**
     * @var \eloquentFilter\QueryFilter\Core\FilterBuilder\ResponseFilter
     */
    public ResponseFilter $responseFilter;

    /**
     * @var \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper
     */
    public QueryBuilderWrapper $queryBuilderWrapper;

    /**
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore $core
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\RequestFilter $requestFilter
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\ResponseFilter $responseFilter
     */
    public function __construct(QueryFilterCore $core, RequestFilter $requestFilter, ResponseFilter $responseFilter)
    {
        $this->queryFilterCore = $core;
        $this->requestFilter = $requestFilter;
        $this->responseFilter = $responseFilter;
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
     *
     * @return void
     */
    public function apply($builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detections_injected = null)
    {
        $this->setQueryBuilderWrapper(QueryBuilderWrapperFactory::createQueryBuilder($builder));

        if (!empty($request)) {
            $this->requestFilter->setPureRequest($request);
        }

        if (!config('eloquentFilter.enabled') || empty($this->requestFilter->getRequest())) {
            return $builder;
        }

        $this->handleRequest($ignore_request, $accept_request);

        $this->resolveDetections($detections_injected);

        return $this->getQueryBuilderWrapper()->getModel()->getResponseFilter($this->responseFilter->getResponse());
    }

    /**
     * @return void
     */
    private function resolveDetections($detections_injected)
    {
        $this->queryFilterCore->setDetectionsInjected($detections_injected);

        /** @see ResolverDetections */
        app()->bind('ResolverDetections', function () {
            return new ResolverDetections($this->getQueryBuilderWrapper()->getBuilder(), $this->requestFilter->getRequest(), $this->queryFilterCore->getDetectFactory());
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
        $serialize_request_filter = $this->getQueryBuilderWrapper()->getModel()->serializeRequestFilter($this->requestFilter->getRequest());

        $alias_list_filter = $this->getQueryBuilderWrapper()->getAliasListFilter();

        $this->requestFilter->requestAlter($ignore_request, $accept_request, $serialize_request_filter, $alias_list_filter, $this->getQueryBuilderWrapper()->getModel());
    }
}
