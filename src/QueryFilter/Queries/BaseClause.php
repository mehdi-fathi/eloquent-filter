<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseClause.
 */
abstract class BaseClause
{
    /**
     * @var
     */
    protected $filter;
    /**
     * @var
     */
    protected $values;

    /**
     * BaseClause constructor.
     *
     * @param $values
     * @param $filter
     */
    public function __construct($values, $filter)
    {
        $this->values = $values;
        $this->filter = $filter;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $nextFilter
     *
     * @return Builder
     */
    public function handle(Builder $query, $nextFilter): Builder
    {
        $query = $nextFilter($query);

        return $this->apply($query);
    }

    /**
     * @param $query
     *
     * @return Builder
     */
    abstract protected function apply($query): Builder;
}
