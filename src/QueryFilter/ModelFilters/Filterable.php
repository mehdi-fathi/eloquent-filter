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
     * @var null
     */
    protected $accept_request = null;

    /**
     * @var bool
     */
    protected $load_default_detection = true;

    /**
     * @var null
     */
    protected $object_custom_detect = null;

    /**
     * @param            $query
     * @param array|null $request
     *
     * @return Builder
     */
    public function scopeFilter($query, ?array $request = null)
    {
        return EloquentFilter::apply($query, $request, $this->ignore_request, $this->accept_request, $this->getObjectCustomDetect());
    }

    /**
     * @param            $query
     * @param array|null $request
     */
    public function scopeIgnoreRequest($query, ?array $request = null)
    {
        $this->ignore_request = $request;
    }

    /**
     * @param            $query
     * @param array|null $request
     */
    public function scopeAcceptRequest($query, ?array $request = null)
    {
        $this->accept_request = $request;
    }

    /**
     * @param $query
     * @param array|null $object_custom_detect
     */
    public function scopeSetCustomDetection($query, ?array $object_custom_detect = null)
    {
        $this->setObjectCustomDetect($object_custom_detect);
    }

    /**
     * @return mixed
     */
    private function getObjectCustomDetect()
    {
        if (method_exists($this, 'EloquentFilterCustomDetection') && empty($this->object_custom_detect) && $this->getLoadDefaultDetection()) {
            $this->setObjectCustomDetect($this->EloquentFilterCustomDetection());
        }

        return $this->object_custom_detect;
    }

    /**
     * @param mixed $object_custom_detect
     */
    private function setObjectCustomDetect($object_custom_detect): void
    {
        $this->object_custom_detect = $object_custom_detect;
    }

    /**
     * @return mixed
     */
    public static function getWhiteListFilter(): array
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
     * @param array $array
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

    /**
     * @param $query
     * @param $load_default_detection
     */
    public function scopeSetLoadDefaultDetection($query, $load_default_detection)
    {
        $this->load_default_detection = $load_default_detection;
    }

    /**
     * @return bool
     */
    public function getLoadDefaultDetection(): bool
    {
        return $this->load_default_detection;
    }
}
