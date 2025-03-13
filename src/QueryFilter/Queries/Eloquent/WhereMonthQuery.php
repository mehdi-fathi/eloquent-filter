<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;


use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereMonthQuery.
 */
class WhereMonthQuery extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereMonth($this->filter, $this->values['month']);
    }
} 