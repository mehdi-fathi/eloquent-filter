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
        if (!empty($params[0]['from']) && !empty($params[0]['to'])) {
            $this->queryFilterBuilder->whereBetween($field, $params);
        } elseif (is_array($params[0])) {
            $this->queryFilterBuilder->whereIn("$field", $params[0]);
        } else {
            $this->queryFilterBuilder->where("$field", $params[0]);
        }
    }

}
