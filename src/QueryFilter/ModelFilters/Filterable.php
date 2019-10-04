<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\QueryFilter\QueryFilter;

/**
 * Trait Filterable.
 */
trait Filterable
{
    /**
     * @param \eloquentFilter\QueryFilter\ModelFilters\ModelFilters $query
     * @param \eloquentFilter\QueryFilter\QueryFilter               $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilter $filters) :\Illuminate\Database\Eloquent\Builder
    {
        return $filters->apply($query, $this->getTable());
    }

    /**
     * @return mixed
     */
    public static function getWhiteListFilter()
    {
        return self::$whiteListFilter;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function setWhiteListFilter($value)
    {
        self::$whiteListFilter[]=$value;
    }
}
