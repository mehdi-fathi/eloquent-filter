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
     * @param array $request
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
     * @param Builder $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array $detect_injected
     *
     * @return Builder
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null): Builder
    {
        $this->builder = $builder;

        if (!empty($request)) {
            $this->setRequest($request);
        }
        $this->setFilterRequests($ignore_request, $accept_request, $this->builder->getModel());

        if (!empty($detect_injected)) {
            $this->setDetectInjected($detect_injected);
            $detect_factory = $this->__getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections());
            $this->detect_factory = $detect_factory;
        }

        $model = $this->builder->getModel();

        $filters = collect($this->getRequest())->map(function ($values, $filter) use ($model) {
            return $this->resolve($filter, $values, $model);
        })->toArray();

        $filters = array_reverse($filters, -1);

        return app(Pipeline::class)
            ->send($this->builder)
            ->through($filters)
            ->thenReturn();
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

        return
            new DetectionFactory(
                $detections
            );
    }

    /**
     * @param $filterName
     * @param $values
     * @param $model
     *
     * @return Application|mixed
     * @throws ReflectionException
     *
     */
    private function resolve($filterName, $values, $model)
    {
        $detect = $this->detect_factory::detect($filterName, $values, $model);

        return app($detect, ['filter' => $filterName, 'values' => $values]);
    }
}
