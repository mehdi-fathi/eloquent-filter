<?php

namespace eloquentFilter\QueryFilter\Core\ResolverDetection;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorDbFactoryContract;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;

class ResolverDetectionDb extends ResolverDetections
{

    /**
     * ResolverDetectionDb constructor.
     * @param $builder
     * @param array $request
     * @param \eloquentFilter\QueryFilter\Detection\Contract\DetectorDbFactoryContract $detector_db_factory
     * @param \eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract $main_builder_conditions_contract
     */
    public function __construct($builder, array $request, DetectorDbFactoryContract $detector_db_factory, MainBuilderConditionsContract $main_builder_conditions_contract)
    {
        $this->builder = $builder;
        $this->request = $request;
        $this->detector_db_factory = $detector_db_factory;
        $this->main_builder_conditions = $main_builder_conditions_contract;
    }

    /**
     * @return array
     */
    public function getFiltersDetection(): array
    {
        $filter_detections = collect($this->request)->map(function ($values, $filter){
            return $this->resolve($filter, $values);
        })->reverse()->filter(function ($item) {
            return $item instanceof BaseClause;
        })->toArray();

        $out = Arr::isAssoc($filter_detections) ? $filter_detections : [];

        return $out;
    }

    /**
     * @param $filterName
     * @param $values
     *
     * @return Application|mixed
     * @throws ReflectionException
     *
     */
    protected function resolve($filterName, $values)
    {
        $detectedConditions = $this->detector_db_factory->buildDetections($filterName, $values);

        $builderDriver = $this->main_builder_conditions->build($detectedConditions);

        return app($builderDriver, ['filter' => $filterName, 'values' => $values]);
    }
}
