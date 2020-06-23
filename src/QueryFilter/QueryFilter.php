<?php

namespace eloquentFilter\QueryFilter;

use eloquentFilter\QueryFilter\Queries\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryFilter.
 *
 * @property \eloquentFilter\QueryFilter\Queries\QueryBuilder queryBuilder
 */
class QueryFilter
{
    use HelperFilter;
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var
     */
    protected $builder;
    /**
     * @var
     */
    protected $queryBuilder;

    /**
     * QueryFilter constructor.
     *
     * @param array $request
     */
    public function __construct(?array $request)
    {

        if(!empty($request)){
//            dump($request);

            $this->setRequest($request);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array|null                            $request
     * @param array|null                            $ignore_request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null): Builder
    {
        $this->builder = $builder;
        $this->queryBuilder = new QueryBuilder($builder);

        if(!empty($request)){
            $this->setRequest($request);
        }
        $this->setFilterRequests($ignore_request, $this->builder->getModel());

        if (!empty($this->getRequest())) {
            foreach ($this->getRequest() as $name => $value) {
                $this->resolveQuery($name, $value);
                // It resolve methods in filters class in child
            }
        }

        return $this->builder;
    }
}
