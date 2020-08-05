<?php

namespace eloquentFilter\QueryFilter;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereOrCondition;
use eloquentFilter\QueryFilter\Detection\DetectionFactory;
use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use eloquentFilter\QueryFilter\Queries\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryFilter.
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
        if (!empty($request)) {
            $this->setRequest($request);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array|null                            $request
     * @param array|null                            $ignore_request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null): Builder
    {
        $this->builder = $builder;
        $this->queryBuilder = new QueryBuilder($this->builder, $this->__getDetectorsInstanceArray());
        $ModelFilters = new ModelFilters($this->builder, $this->queryBuilder);

        if (!empty($request)) {
            $this->setRequest($request);
        }
        $this->setFilterRequests($ignore_request, $this->builder->getModel());

        if (!empty($this->getRequest())) {
            foreach ($this->getRequest() as $name => $value) {
                $ModelFilters->resolveQuery($name, $value);
                // It resolve methods in filters class in child
            }
        }

        return $this->builder;
    }

    /**
     * @return DetectionFactory
     */
    private function __getDetectorsInstanceArray()
    {
        return
            new DetectionFactory(
                [
                    WhereBetweenCondition::class,
                    WhereByOptCondition::class,
                    WhereLikeCondition::class,
                    WhereInCondition::class,
                    WhereOrCondition::class,
                    WhereHasCondition::class,
                    WhereCondition::class,
                ]
            );
    }
}
