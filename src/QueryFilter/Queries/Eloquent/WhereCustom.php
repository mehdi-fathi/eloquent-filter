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
     *
     */
    const METHOD_SIGN = "filterCustom";

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
    static public function getMethod($filter): string
    {
        $filter = ucfirst($filter);
        $method = self::METHOD_SIGN . $filter;
        return $method;
    }
}
