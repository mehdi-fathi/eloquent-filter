<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;
use Illuminate\Support\Facades\DB;

class Where extends BaseClause
{
    public function apply($query)
    {
        return $query->where($this->filter, $this->values);
    }
}
