<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;


use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereYearQuery.
 */
class WhereYearQuery extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereYear($this->filter, $this->values['year']);
    }
} 