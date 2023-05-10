<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

class WhereDate extends BaseClause
{
    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query): Builder
    {
        return $query->whereDate($this->filter, $this->values);
    }
}
