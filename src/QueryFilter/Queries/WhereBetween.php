<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereBetween
 * @package eloquentFilter\QueryFilter\Queries
 */
class WhereBetween extends BaseClause
{
    /**
     * @param $query
     * @return Builder
     */
    public function apply($query): Builder
    {

        $start = $this->values['start'];
        $end = $this->values['end'];

        return $query->whereBetween($this->filter, [$start, $end]);
    }
}
