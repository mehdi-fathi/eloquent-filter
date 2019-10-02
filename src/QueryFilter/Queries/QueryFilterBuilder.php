<?php
/**
 * Copyright (c) 2019. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

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
     *
     */
    public function whereBetween($field, array $params)
    {
        $start = $params[0]['from'];
        $end = $params[0]['to'];
        $this->builder->whereBetween($field, [$start, $end]);
    }

    /**
     *
     */
    public function where($field,$value)
    {
       $this->builder->where("$field", $value);
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
