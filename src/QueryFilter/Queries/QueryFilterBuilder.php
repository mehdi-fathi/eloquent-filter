<?php

namespace eloquentFilter\QueryFilter\Queries;

use eloquentFilter\QueryFilter\HelperFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryFilterBuilder.
 */
class QueryFilterBuilder
{
    use HelperFilter;
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
        foreach ($params as $key => $value) {
            if (is_null($value) || $value == '') {
                unset($params[$key]);
            }
        }
        if (!empty($params)) {
            $this->builder->whereIn("$field", $params);
        }
    }

    /**
     * @param       $field
     * @param array $params
     */
    public function like($field, array $params)
    {
        foreach ($params as $key => $value) {
            if (is_null($value) || $value == '') {
                unset($params[$key]);
            }
        }

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->builder->where("$field", 'like', $value['like']);
            }
        }
    }

    /**
     * @param $field
     * @param $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function wherehas($field, $value)
    {
        $field_row = explode('.', $field);
        $field_row = end($field_row);

        $conditions = str_replace("." . $field_row, '', $field);

        return $this->builder->whereHas($conditions,
            function ($q) use ($value, $field_row) {
                $q->where($field_row, $value);
            }
        );
    }

    /**
     * @param $limit
     */
    public function limit(int $limit)
    {
        $this->builder->limit($limit);
    }

    /**
     * @param $field
     * @param $type
     */
    public
    function orderBy(string $field, string $type)
    {
        $this->builder->orderBy($field, $type);
    }
}
