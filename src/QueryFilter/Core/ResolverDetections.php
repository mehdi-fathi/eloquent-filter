<?php

namespace eloquentFilter\QueryFilter\Core;

use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryFilterBuilder;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;

/**
 * Class ResolverDetections
 * @package eloquentFilter\QueryFilter\Core
 */
class ResolverDetections
{
    /**
     * @var
     */
    private $builder;
    /**
     * @var array
     */
    private array $request;
    /**
     * @var \eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract
     */
    private DetectorFactoryContract $detector_factory;


    private MainBuilderConditionsContract $main_builder_conditions;

    /**
     * ResolverDetections constructor.
     * @param $builder
     * @param array $request
     * @param \eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract $detector_factory
     * @param \eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract $main_builder_conditions_contract
     */
    public function __construct($builder, array $request, DetectorFactoryContract $detector_factory, MainBuilderConditionsContract $main_builder_conditions_contract)
    {
        $this->builder = $builder;
        $this->request = $request;
        $this->detector_factory = $detector_factory;
        $this->main_builder_conditions = $main_builder_conditions_contract;
    }

    /**
     * @return mixed
     * @see QueryFilterBuilder
     */
    public function getResolverOut()
    {
        $filter_detections = $this->getFiltersDetection();

        $out = app(Pipeline::class)
            ->send($this->builder)
            ->through($filter_detections)
            ->thenReturn();

        return $out;
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
        $detectedConditions = $this->detector_factory->buildDetections($filterName, $values, $model);

        $builderDriver = $this->main_builder_conditions->build($detectedConditions);

        return app($builderDriver, ['filter' => $filterName, 'values' => $values]);
    }

    /**
     * @return array
     */
    private function getFiltersDetection(): array
    {
        if (app('eloquentFilter')->getNameDriver() != DBBuilderQueryByCondition::NAME) {
            $model = $this->builder->getModel();
        } else {
            $model = $this->builder->from;
        }

        $filter_detections = collect($this->request)->map(function ($values, $filter) use ($model) {
            return $this->resolve($filter, $values, $model);
        })->reverse()->filter(function ($item) {
            return $item instanceof BaseClause;
        })->toArray();

        $out = Arr::isAssoc($filter_detections) ? $filter_detections : [];

        return $out;
    }
}
