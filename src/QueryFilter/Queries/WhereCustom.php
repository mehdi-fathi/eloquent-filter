<?php


namespace eloquentFilter\QueryFilter\Queries;


use Illuminate\Database\Eloquent\Builder;

class WhereCustom extends BaseClause
{

    public function apply($query): Builder
    {
        return $query->getModel()->{$this->filter}($query, $this->values);
    }

}
