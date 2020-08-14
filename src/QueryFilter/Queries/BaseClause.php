<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseClause
{

    protected $query;
    protected $filter;
    protected $values;

    public function __construct($values, $filter)
    {
        $this->values = $values;
        $this->filter = $filter;
    }

    public function handle($query, $nextFilter)
    {
        $query = $nextFilter($query);
        return static::apply($query);
    }

    abstract protected function apply($query): Builder;
}
