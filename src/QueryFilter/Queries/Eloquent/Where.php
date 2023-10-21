<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;

/**
 *
 */
class Where extends BaseClause
{
    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        return $query->where($this->filter, $this->values);
    }
}
