<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class WhereIn.
 */
class WhereIn extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        return DB::table($query->getModel()->getTable())->whereIn($this->filter, $this->values);
    }
}
