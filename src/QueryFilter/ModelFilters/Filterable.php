<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\Facade\EloquentFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable.
 */
trait Filterable
{
    /**
     * @var null
     */
    protected $ignore_request = null;

    /**
     * @var
     */
    protected $object_custom_detect = null;

    /**
     * @param            $query
     * @param array|null $reqesut
     *
     * @return Builder
     */
    public function scopeFilter($query, ?array $reqesut = null): Builder
    {
        return EloquentFilter::apply($query, $reqesut, $this->ignore_request, $this->getObjectCustomDetect());
    }

    /**
     * @param            $query
     * @param array|null $reqesut
     *
     * @return $this
     */
    public function scopeIgnoreRequest($query, ?array $reqesut = null)
    {
        $this->ignore_request = $reqesut;

        return $this;
    }

    /**
     * @param $query
     * @param array|null $object_custom_detect
     *
     * @return $this
     */
    public function scopeSetCustomDetection($query, ?array $object_custom_detect = null)
    {
        $this->setObjectCustomDetect($object_custom_detect);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectCustomDetect()
    {
        if (method_exists($this, 'EloquentFilterCustomDetection') && empty($this->object_custom_detect)) {
            $this->setObjectCustomDetect($this->EloquentFilterCustomDetection());
        }

        return $this->object_custom_detect;
    }

    /**
     * @param mixed $object_custom_detect
     */
    public function setObjectCustomDetect($object_custom_detect): void
    {
        $this->object_custom_detect = $object_custom_detect;
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

    /**
     * @param string $method
     *
     * @return bool
     */
    public function checkModelHasOverrideMethod(string $method): bool
    {
        return (bool) method_exists($this, $method);
    }
}
