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
    private $reserve_param = [
        'f_params' => [
            'limit',
            'orderBy',
        ],
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

            $this->__buildQueryWithNewParams($field, $params[0]);
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
        foreach ($params as $key => $param) {

            if (!in_array($key, $this->reserve_param['f_params'])) {
                throw new \Exception("$key is not in f_params array.");
            }
            if (is_array($param)) {
                $this->queryFilterBuilder->$key($param['field'], $param['type']);
            } else {
                $this->queryFilterBuilder->$key($param);
            }
        }

    }
}
