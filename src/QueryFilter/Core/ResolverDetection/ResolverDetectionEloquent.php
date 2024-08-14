<?php

namespace eloquentFilter\QueryFilter\Core\ResolverDetection;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;

class ResolverDetectionEloquent extends ResolverDetections
{

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
     * @return array
     */
    public function getFiltersDetection(): array
    {
        $model = $this->builder->getModel();

        $filter_detections = collect($this->request)->map(function ($values, $filter) use ($model) {
            return $this->resolve($filter, $values, $model);
        })->reverse()->filter(function ($item) {
            return $item instanceof BaseClause;
        })->toArray();

        $out = Arr::isAssoc($filter_detections) ? $filter_detections : [];

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
    protected function resolve($filterName, $values, $model)
    {
        $detectedConditions = $this->detector_factory->buildDetections($filterName, $values, $model);

        $builderDriver = $this->main_builder_conditions->build($detectedConditions);

        return app($builderDriver, ['filter' => $filterName, 'values' => $values]);
    }
}
