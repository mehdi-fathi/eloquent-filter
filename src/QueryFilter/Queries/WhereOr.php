<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereOr.
 */
class WhereOr extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        $field = key($this->values);
        $value = reset($this->values);

        return $query->orWhere($field, $value);
    }
}
