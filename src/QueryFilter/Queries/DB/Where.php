<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;
use Illuminate\Support\Facades\DB;

class Where extends BaseClause
{
    public function apply($query)
    {
        return DB::table($query->getModel()->getTable())->where($this->filter, $this->values);
    }
}
