<?php

namespace eloquentFilter\QueryFilter\Queries;

use eloquentFilter\QueryFilter\Detection\DetectorConditions;
use eloquentFilter\QueryFilter\Detection\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\WhereCondition;
use eloquentFilter\QueryFilter\Detection\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\WhereOrCondition;
use eloquentFilter\QueryFilter\HelperFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryBuilder.
 */
class QueryBuilder
{
    use HelperFilter;
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
        } elseif ($method_builder_detcted == 'orWhere') {
            $field = key($params);
            $value = reset($params);
            $this->queryFilterBuilder->orWhere($field, $value);
        } elseif (!empty($method_builder_detcted)) {
            $this->queryFilterBuilder->$method_builder_detcted($field, $params);
        }
    }

    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    private function detectMethodByParams($field, $params)
    {
        $detect = new DetectorConditions(
            [
                new WhereBetweenCondition(),
                new WhereByOptCondition(),
                new WhereLikeCondition(),
                new WhereInCondition(),
                new WhereOrCondition(),
                new WhereHasCondition(),
                new WhereCondition(),
            ]
        );
        $method = $detect->detect($field, $params);

        return $method ?? null;
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
