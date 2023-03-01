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
    protected bool $load_injected_detections = true;

    /**
     * @var null
     */
    protected $object_custom_detect = null;

    /**
     * @param            $builder
     * @param array|null $request
     *
     * @return Builder
     */
    public function scopeFilter($builder, ?array $request = null)
    {
        /** @see QueryFilterBuilder::apply() */
        return EloquentFilter::apply($builder, $request, $this->ignore_request, $this->accept_request, $this->getObjectCustomDetect());
    }

    /**
     * @param            $builder
     * @param array|null $request
     */
    public function scopeIgnoreRequest($builder, ?array $request = null)
    {
        $this->ignore_request = $request;
    }

    /**
     * @param            $builder
     * @param array|null $request
     */
    public function scopeAcceptRequest($builder, ?array $request = null)
    {
        $this->accept_request = $request;
    }

    /**
     * @param $builder
     * @param array|null $object_custom_detect
     */
    public function scopeSetCustomDetection($builder, ?array $object_custom_detect = null)
    {
        $this->setObjectCustomDetect($object_custom_detect);
    }

    /**
     * @return mixed
     */
    private function getObjectCustomDetect()
    {
        if (method_exists($this, 'EloquentFilterCustomDetection') && empty($this->object_custom_detect) && $this->getLoadInjectedDetections()) {
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
        return (self::$whiteListFilter ?? []);
    }

    /**
     * @return mixed
     */
    public function getAliasListFilter(): ?array
    {
        return ($this->aliasListFilter ?? null);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function addWhiteListFilter($value)
    {
        if (isset(self::$whiteListFilter)) {
            self::$whiteListFilter[] = $value;
        }
    }

    /**
     * @param array $array
     */
    public static function setWhiteListFilter(array $array)
    {
        if (isset(self::$whiteListFilter)) {
            self::$whiteListFilter = $array;
        }
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public function checkModelHasOverrideMethod(string $method): bool
    {
        return method_exists($this, $method);
    }

    /**
     * @param $builder
     * @param $load_default_detection
     */
    public function scopeSetLoadInjectedDetection($builder, $load_default_detection)
    {
        $this->load_injected_detections = $load_default_detection;
    }

    /**
     * @return bool
     */
    public function getLoadInjectedDetections(): bool
    {
        return $this->load_injected_detections;
    }

    /**
     * @return null
     */
    public function getResponseFilter($response)
    {
        return $response;
    }

    /**
     * @return null
     */
    public function serializeRequestFilter($request)
    {
        return $request;
    }
}
