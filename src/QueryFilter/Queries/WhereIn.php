<?php


namespace eloquentFilter\QueryFilter\Queries;


use Illuminate\Database\Eloquent\Builder;

class WhereIn extends BaseClause
{

    public function apply($query): Builder
    {
        return $query->whereIn($this->filter, $this->values);
    }
}
