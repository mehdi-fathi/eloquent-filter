<?php

namespace eloquentFilter\QueryFilter\Queries;

use eloquentFilter\QueryFilter\Detection\Detector;
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
     * @var Detector
     */
    private $detector;

    /**
     * @var
     */
    private $queryFilterBuilder;

    /**
     * QueryBuilder constructor.
     *
     * @param Builder  $builder
     * @param Detector $detector
     */
    public function __construct(Builder $builder, Detector $detector)
    {
        $this->builder = $builder;
        $this->queryFilterBuilder = new QueryFilterBuilder($builder);
        $this->detector = $detector;
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
        $method = $this->detector::detect($field, $params);

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
