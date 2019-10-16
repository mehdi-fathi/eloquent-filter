<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryBuilder.
 */
class QueryBuilder
{
    /**
     * @var array
     */
    private $_reserve_param = [
        'f_params' => [
            'limit',
            'orderBy',
        ]
    ];

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
     *
     * @throws \Exception
     */
    public function buildQuery($field, array $params)
    {
        if (!empty($params[0]['start']) && !empty($params[0]['end'])) {

            $this->queryFilterBuilder->whereBetween($field, $params);

        } elseif ($field == 'f_params') {

            $this->__buildQueryWithNewParams($field, $params);

        } elseif (!empty($params[0]['operator']) && !empty($params[0]['value'])) {

            $this->queryFilterBuilder->whereByOpt($field, $params);

        } elseif (is_array($params[0])) {

            $this->queryFilterBuilder->whereIn("$field", $params[0]);

        } else {
            $this->queryFilterBuilder->where("$field", $params[0]);
        }
    }

    /**
     * @param       $field
     * @param array $params
     *
     * @throws \Exception
     */
    private function __buildQueryWithNewParams($field, array $params)
    {
        $method = key($params[0]);
        if (!in_array($method, $this->_reserve_param['f_params'])) {
            throw new \Exception("$method is not in f_params array.");
        }

        $value = $params[0][$method];
        if (is_array($value)) {
            $this->queryFilterBuilder->$method($value['field'], $value['type']);
        } else {
            $this->queryFilterBuilder->$method($value);
        }

    }
}
