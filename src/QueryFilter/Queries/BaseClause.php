<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseClause.
 */
abstract class BaseClause
{

    /**
     * BaseClause constructor.
     *
     * @param $values
     * @param $filter
     */
    public function __construct(protected $values, protected $filter)
    {
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
