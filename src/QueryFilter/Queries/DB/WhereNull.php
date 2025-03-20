<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;

/**
 * Class WhereNull.
 */
class WhereNull extends BaseClause
{
    /**
     * @param $query
     * @param $field
     * @param $params
     *
     * @return mixed
     */
    public function apply($query)
    {
        return $query->whereNull($this->filter);
    }
} 