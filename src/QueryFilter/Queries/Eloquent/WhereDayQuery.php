<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;


use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereMonthQuery.
 */
class WhereDayQuery extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereDay($this->filter, $this->values['day']);
    }
}