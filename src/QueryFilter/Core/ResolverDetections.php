<?php

namespace eloquentFilter\QueryFilter\Core;

use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryFilterBuilder;
use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
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
    private DetectorFactoryContract $detect_factory;

    /**
     * ResolverDetections constructor.
     * @param $builder
     * @param array $request
     * @param \eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract $detect_factory
     */
    public function __construct($builder, array $request, DetectorFactoryContract $detect_factory)
    {
        $this->builder = $builder;
        $this->request = $request;
        $this->detect_factory = $detect_factory;
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
        $detectedConditions = $this->detect_factory->buildDetections($filterName, $values, $model);

        return app($detectedConditions, ['filter' => $filterName, 'values' => $values]);
    }

    /**
     * @return array
     */
    private function getFiltersDetection(): array
    {
        $model = $this->builder->getModel();

        $filter_detections = collect($this->request)->map(function ($values, $filter) use ($model) {
            // dd($filter, $values, $model);
            return $this->resolve($filter, $values, $model);
        })->reverse()->filter(function ($item) {
            return $item instanceof BaseClause;
        })->toArray();

        $out = Arr::isAssoc($filter_detections) ? $filter_detections : [];

        return $out;
    }
}
