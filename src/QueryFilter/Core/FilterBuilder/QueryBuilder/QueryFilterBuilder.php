<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\QueryBuilder;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\EloquentModelBuilderWrapper;
use eloquentFilter\QueryFilter\Core\FilterBuilder\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Core\ResolverDetections;

/**
 * Class QueryFilterBuilder.
 */
abstract class QueryFilterBuilder
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
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     *
     * @return mixed
     * @throws \ReflectionException
     */
    abstract public function apply($builder, array $detections_injected = null, array $black_list_detections = null): mixed;

}
