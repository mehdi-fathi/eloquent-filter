<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

class Where extends BaseClause
{
    public function apply($query): Builder
    {
        return $query->where($this->filter, $this->values);
    }
}
