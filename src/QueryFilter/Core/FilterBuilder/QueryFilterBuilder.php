<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Core\ResolverDetections;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;
use Illuminate\Database\Eloquent\Builder;

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
     * @param Builder $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detect_injected
     *
     * @return void
     */
    public function apply($builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null)
    {
        $this->queryBuilderWrapper = QueryBuilderWrapperFactory::createQueryBuilder($builder);

        if (!empty($request)) {
            $this->requestFilter->setRequest($request);
        }

        if (!config('eloquentFilter.enabled') || empty($this->requestFilter->getRequest())) {
            return $builder;
        }

        $this->handleRequest($ignore_request, $accept_request);

        $this->setInjectedDetections($detect_injected);

        $this->resolveDetections();

        return $this->queryBuilderWrapper->getModel()->getResponseFilter($this->responseFilter->getResponse());
    }

    /**
     * @param array|null $injected_detections
     * @return void
     */
    private function setInjectedDetections(?array $injected_detections): void
    {
        if (!empty($injected_detections)) {
            $this->queryFilterCore->setInjectedDetections($injected_detections);
            $this->queryFilterCore->setDetectFactory($this->queryFilterCore->getDetectorFactory($this->queryFilterCore->getDefaultDetect(), $this->queryFilterCore->getInjectedDetections()));
        }
    }

    /**
     * @return mixed
     */
    private function resolveDetections()
    {
        /** @see ResolverDetections */
        app()->bind('ResolverDetections', function () {
            return new ResolverDetections($this->queryBuilderWrapper->getBuilder(), $this->requestFilter->getRequest(), $this->queryFilterCore->getDetectFactory());
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
        $serializeRequestFilter = null;
        if (method_exists($this->queryBuilderWrapper->getModel(), 'serializeRequestFilter') && !empty($this->requestFilter->getRequest())) {
            $serializeRequestFilter = $this->queryBuilderWrapper->serializeRequestFilter($this->requestFilter->getRequest());
        }

        $alias_list_filter = $this->queryBuilderWrapper->getAliasListFilter();

        $this->requestFilter->requestAlter($ignore_request, $accept_request, $serializeRequestFilter, $alias_list_filter, $this->queryBuilderWrapper->getModel());
    }


}
