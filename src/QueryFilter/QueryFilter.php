<?php

namespace eloquentFilter\QueryFilter;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereCustomCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereOrCondition;
use eloquentFilter\QueryFilter\Detection\DetectionFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use ReflectionException;

/**
 * Class QueryFilter.
 */
class QueryFilter
{
    use HelperFilter;
    use HelperEloquentFilter;

    /**
     * @var
     */
    protected $request;
    /**
     * @var
     */
    protected $builder;

    /**
     * @var
     */
    protected $detect;

    /**
     * @var
     */
    protected $accept_request;

    /**
     * @var
     */
    protected $ignore_request;

    /**
     * @var
     */
    protected $detect_injected;

    /**
     * @var
     */
    protected $default_detect;

    /**
     * @var DetectionFactory
     */
    private $detect_factory;

    /**
     * QueryFilter constructor.
     *
     * @param array      $request
     * @param array|null $detect_injected
     */
    public function __construct(?array $request, array $detect_injected = null)
    {
        if (!empty($request)) {
            $this->setRequest($request);
        }
        if (!empty($detect_injected)) {
            $this->setDetectInjected($detect_injected);
        }

        $this->setDefaultDetect($this->__getDefaultDetectorsInstance());
        $this->detect_factory = $this->__getDetectorFactory($this->getDefaultDetect(), $this->getDetectInjected());
    }

    /**
     * @return array
     */
    private function __getDefaultDetectorsInstance(): array
    {
        return [
            SpecialCondition::class,
            WhereCustomCondition::class,
            WhereBetweenCondition::class,
            WhereByOptCondition::class,
            WhereLikeCondition::class,
            WhereInCondition::class,
            WhereOrCondition::class,
            WhereHasCondition::class,
            WhereCondition::class,
        ];
    }

    /**
     * @param mixed $default_detect
     */
    public function setDefaultDetect($default_detect): void
    {
        $this->default_detect = $default_detect;
    }

    /**
     * @return mixed
     */
    public function getDefaultDetect()
    {
        return $this->default_detect;
    }

    /**
     * @param array $detect
     */
    public function setDetect(array $detect): void
    {
        $this->detect = $detect;
    }

    /**
     * @return array
     */
    public function getDetect()
    {
        return $this->detect;
    }

    /**
     * @param mixed $detect_injected
     */
    public function setDetectInjected($detect_injected): void
    {
        if (config('eloquentFilter.enabled_custom_detection') == false) {
            return;
        }
        $this->detect_injected = $detect_injected;
    }

    /**
     * @return mixed
     */
    public function getDetectInjected()
    {
        return $this->detect_injected;
    }

    /**
     * @param Builder    $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array      $detect_injected
     *
     * @return
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null)
    {
        if (config('eloquentFilter.enabled') == false) {
            return;
        }
        $this->builder = $builder;

        if (!empty($request)) {
            $this->setRequest($request);
        }

        $this->__handelSerializeRequestFilter();

        $this->setFilterRequests($ignore_request, $accept_request, $this->builder->getModel());

        if (!empty($detect_injected)) {
            $this->setDetectInjected($detect_injected);
            $detect_factory = $this->__getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections());
            $this->detect_factory = $detect_factory;
        }

        $filter_detections = $this->getFilterDetections();

        $out = app(Pipeline::class)
            ->send($this->builder)
            ->through($filter_detections)
            ->thenReturn();

        $out = $this->__handelResponseFilter($out);

        return $out;
    }

    private function __handelResponseFilter($out)
    {
        if (method_exists($this->builder->getModel(), 'ResponseFilter')) {
            return $this->builder->getModel()->ResponseFilter($out);
        }

        return $out;
    }

    private function __handelSerializeRequestFilter()
    {
        if (method_exists($this->builder->getModel(), 'serializeRequestFilter')) {
            if (!empty($this->getRequest())) {
                $serializeRequestFilter = $this->builder->getModel()->serializeRequestFilter($this->getRequest());
                $this->setRequest($serializeRequestFilter);
            }
        }
    }

    /**
     * @param array|null $default_detect
     * @param array|null $detect_injected
     *
     * @return DetectionFactory
     */
    private function __getDetectorFactory(array $default_detect = null, array $detect_injected = null)
    {
        $detections = $default_detect;

        if (!empty($detect_injected)) {
            $detections = array_merge($detect_injected, $default_detect);
        }

        $this->setDetect($detections);

        return app(DetectionFactory::class, ['detections' => $detections]);
    }

    /**
     * @param $filterName
     * @param $values
     * @param $model
     *
     * @throws ReflectionException
     *
     * @return Application|mixed
     */
    private function resolve($filterName, $values, $model)
    {
        $detect = $this->detect_factory::detect($filterName, $values, $model);

        return app($detect, ['filter' => $filterName, 'values' => $values]);
    }

    /**
     * @return array
     */
    private function getFilterDetections(): array
    {
        $model = $this->builder->getModel();

        $filter_detections = collect($this->getRequest())->map(function ($values, $filter) use ($model) {
            return $this->resolve($filter, $values, $model);
        })->reverse()->toArray();

        return $filter_detections;
    }
}
