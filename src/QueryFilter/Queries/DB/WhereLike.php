<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class WhereLike.
 */
class WhereLike extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        return DB::table($query->getModel()->getTable())->where("$this->filter", 'like', $this->values['like']);
    }
}
