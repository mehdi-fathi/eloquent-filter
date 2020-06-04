<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\Facade\EloquentFilter;

/**
 * Trait Filterable.
 */
trait Filterable
{

    /**
     * @param            $query
     * @param array|null $reqesut
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, ?array $reqesut = null): \Illuminate\Database\Eloquent\Builder
    {
        return EloquentFilter::apply($query, $reqesut);
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
    public static function addWhiteListFilter($value)
    {
        self::$whiteListFilter[] = $value;
    }

    /**
     * @param $array
     *
     * @return mixed
     */
    public static function setWhiteListFilter(array $array)
    {
        self::$whiteListFilter = $array;
    }
}
