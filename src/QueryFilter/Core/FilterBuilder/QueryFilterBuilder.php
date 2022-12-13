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
     * @var \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper
     */
    public QueryBuilderWrapper $queryBuilderWrapper;

    /**
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore $core
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\RequestFilter $requestFilter
     */
    public function __construct(QueryFilterCore $core, RequestFilter $requestFilter)
    {
        $this->queryFilterCore = $core;
        $this->requestFilter = $requestFilter;
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
            return;
        }

        $this->requestHandel($ignore_request, $accept_request);

        $this->setInjectedDetections($detect_injected);

        $response = $this->resolveDetections();

        $response = $this->responseFilterHandler($response);

        return $response;
    }

    /**
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @return void
     */
    private function requestHandel(?array $ignore_request, ?array $accept_request): void
    {
        if (method_exists($this->queryBuilderWrapper->getModel(), 'serializeRequestFilter') && !empty($this->requestFilter->getRequest())) {
            $serializeRequestFilter = $this->queryBuilderWrapper->serializeRequestFilter($this->requestFilter->getRequest());
            $this->requestFilter->handelSerializeRequestFilter($serializeRequestFilter);
        }

        if ($alias_list_filter = $this->queryBuilderWrapper->getAliasListFilter() ?? null) {
            $this->requestFilter->makeAliasRequestFilter($alias_list_filter);
        }

        $this->requestFilter->setFilterRequests($ignore_request, $accept_request, $this->queryBuilderWrapper->getModel());
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
        $response = app('ResolverDetections')->getResolverOut();
        return $response;
    }

    /**
     * @param $out
     *
     * @return mixed
     */
    public function responseFilterHandler($out)
    {
        if (method_exists($this->queryBuilderWrapper->getModel(), 'ResponseFilter')) {
            return $this->queryBuilderWrapper->responseFilter($out);
        }

        return $out;
    }
}
