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
        $jdate = $this->convertJdateToG($params[0]);
        if($jdate){
            $start = $jdate['start'];
            $end = $jdate['end'];
        }
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
    public function orderBy(string $field, string $type)
    {
        $this->builder->orderBy($field, $type);
    }
}
