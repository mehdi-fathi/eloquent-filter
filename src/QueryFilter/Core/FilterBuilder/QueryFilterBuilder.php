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
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore $queryFilterCore
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\RequestFilter $requestFilter
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\ResponseFilter $responseFilter
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

        $this->handleRequest(
            ignore_request: $ignore_request,
            accept_request: $accept_request
        );

        $this->resolveDetections($detections_injected, $black_list_detections);

        return $this->getQueryBuilderWrapper()->getModel()->getResponseFilter($this->responseFilter->getResponse());
    }

    /**
     * @return void
     */
    private function resolveDetections($detections_injected, $black_list_detections)
    {
        $this->queryFilterCore->unsetDetection($black_list_detections);
        $this->queryFilterCore->reload();

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

        $this->requestFilter->requestAlter(
            ignore_request: $ignore_request,
            accept_request: $accept_request,
            serialize_request_filter: $serialize_request_filter,
            alias_list_filter: $alias_list_filter,
            model: $this->getQueryBuilderWrapper()->getModel(),
        );
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
}
