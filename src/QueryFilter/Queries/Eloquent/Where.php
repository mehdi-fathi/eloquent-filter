<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class Where extends BaseClause
{
    public function apply($query): Builder
    {
        return $query->where($this->filter, $this->values);
    }
}
