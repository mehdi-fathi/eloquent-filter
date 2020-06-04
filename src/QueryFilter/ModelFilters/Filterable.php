<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\Facade\EloquentFilter;

/**
 * Trait Filterable.
 */
trait Filterable
{

    protected $ignore_request =null;
    /**
     * @param            $query
     * @param array|null $reqesut
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, ?array $reqesut = null): \Illuminate\Database\Eloquent\Builder
    {
        return EloquentFilter::apply($query, $reqesut,$this->ignore_request);
    }

    public function scopeIgnoreRequest($query, ?array $reqesut = null)
    {
        $this->ignore_request = $reqesut;

        return $this;

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
