<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;

/**
 * Class WhereCustom.
 */
class WhereCustom extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        $method = $this->getMethod($this->filter);
        return $query->getModel()->$method($query, $this->values);
    }

    /**
     * @param $filter
     * @return string
     */
    public static function getMethod($filter): string
    {
        $custom_method_sign = config('eloquentFilter.custom_method_sign');

        $filter = ucfirst($filter);
        $method = $custom_method_sign . $filter;
        return $method;
    }
}
