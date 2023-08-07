<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;

class WhereDate extends BaseClause
{
    /**
     * @param $query
     * @return \Illuminate\Database\DB\Builder
     */
    public function apply($query)
    {
        return $query->whereDate($this->filter, $this->values);
    }
}
