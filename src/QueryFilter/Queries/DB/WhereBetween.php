<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;

/**
 * Class WhereBetween.
 */
class WhereBetween extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        $start = $this->values['start'];
        $end = $this->values['end'];

        return $query->whereBetween($this->filter, [$start, $end]);
    }
}
