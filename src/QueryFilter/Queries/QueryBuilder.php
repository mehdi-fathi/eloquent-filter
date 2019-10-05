<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryBuilder.
 */
class QueryBuilder
{
    /**
     * @var
     */
    private $builder;

    /**
     * @var
     */
    private $queryFilterBuilder;

    /**
     * QueryBuilder constructor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
        $this->queryFilterBuilder = new QueryFilterBuilder($builder);
    }

    /**
     * @param       $field
     * @param array $params
     */
    public function buildQuery($field, array $params)
    {
        if (!empty($params[0]['start']) && !empty($params[0]['end'])) {
            $this->queryFilterBuilder->whereBetween($field, $params);
        } elseif (!empty($params[0]['operator']) && !empty($params[0]['value'])) {
            $this->queryFilterBuilder->whereByOpt($field, $params);
        } elseif (is_array($params[0])) {
            $this->queryFilterBuilder->whereIn("$field", $params[0]);
        } else {
            $this->queryFilterBuilder->where("$field", $params[0]);
        }
    }
}
