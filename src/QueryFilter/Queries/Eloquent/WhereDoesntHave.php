<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;

/**
 *
 */
class WhereDoesntHave extends BaseClause
{
    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        return $query->doesntHave($this->values);
    }
}
