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
    public function buildQuery($field, $params)
    {
        $method_builder_detcted = $this->detectMethodByParams($field, $params);

        if ($field == 'f_params') {
            $this->__buildQueryBySpecialParams($field, $params);
        }else{
            $this->queryFilterBuilder->$method_builder_detcted($field, $params);
        }
    }

    private function detectMethodByParams($field, $params)
    {
        if (!empty($params['start']) && !empty($params['end'])) {
            $method = 'whereBetween';
        } elseif (!empty($params['operator']) && !empty($params['value'])) {
            $method = 'whereByOpt';
        } elseif (!empty($params['like'])) {
            $method = 'like';
        } elseif (is_array($params)) {
            $method = 'whereIn';
        } elseif (stripos($field, '.')) {
            $method = 'wherehas';
        } else {
            $method = 'where';
        }
        return $method;
    }

    /**
     * @param       $field
     * @param array $params
     *
     * @throws \Exception
     */
    private function __buildQueryBySpecialParams($field, array $params)
    {
        foreach ($params as $key => $param) {
            if (!in_array($key, $this->reserve_param['f_params'])) {
                throw new \Exception("$key is not in f_params array."); //TODO make exception test for it
            }
            if (is_array($param)) {
                $this->queryFilterBuilder->$key($param['field'], $param['type']);
            } else {
                $this->queryFilterBuilder->$key($param);
            }
        }
    }
}
