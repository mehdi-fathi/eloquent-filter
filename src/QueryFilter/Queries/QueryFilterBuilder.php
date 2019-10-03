<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryFilterBuilder
 *
 * @package eloquentFilter\QueryFilter\Queries
 */
class QueryFilterBuilder
{
    /**
     * @var
     */
    protected $builder;

    /**
     * QueryBuilder constructor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param       $field
     * @param array $params
     */
    public function whereBetween($field, array $params)
    {
        $start = $params[0]['start'];
        $end = $params[0]['end'];
        $this->builder->whereBetween($field, [$start, $end]);
    }

    /**
     * @param $field
     * @param $value
     */
    public function where($field, $value)
    {
        $this->builder->where("$field", $value);
    }

    /**
     * @param $field
     * @param $params
     */
    public function whereByOpt($field, $params)
    {
        $opt = $params[0]['operator'];
        $value = $params[0]['value'];
        $this->builder->where("$field", "$opt", $value);
    }

    /**
     * @param       $field
     * @param array $params
     */
    public function whereIn($field, array $params)
    {
        $this->builder->whereIn("$field", $params);
    }
}
