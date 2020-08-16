<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

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
    public function apply($query): Builder
    {
        return $query->whereIn($this->filter, $this->values);
    }
}
