<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;

/**
 * Class WhereNotNull.
 */
class WhereNotNull extends BaseClause
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
        return $query->whereNotNull($this->filter);
    }
} 