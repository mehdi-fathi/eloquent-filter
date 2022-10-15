<?php

namespace eloquentFilter\QueryFilter\Core;

use eloquentFilter\QueryFilter\Core\FilterBuilder\QueryFilterBuilder;
use eloquentFilter\QueryFilter\Detection\DetectorContract;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pipeline\Pipeline;

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
     * @var \eloquentFilter\QueryFilter\Detection\DetectorContract
     */
    private DetectorContract $detect_factory;

    /**
     * ResolverDetections constructor.
     * @param $builder
     * @param array|null $request
     * @param \eloquentFilter\QueryFilter\Detection\DetectorContract $detect_factory
     */
    public function __construct($builder, array $request, DetectorContract $detect_factory)
    {
        $this->builder = $builder;
        $this->request = $request;
        $this->detect_factory = $detect_factory;
    }

    /**
     * @return mixed
     * @see QueryFilterBuilder
     */
    public function getResolverOut(): Builder
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
        $detect = $this->detect_factory->detect($filterName, $values, $model);

        return app($detect, ['filter' => $filterName, 'values' => $values]);
    }

    /**
     * @return array
     */
    private function getFiltersDetection(): array
    {
        $model = $this->builder->getModel();

        $filter_detections = collect($this->request)->map(function ($values, $filter) use ($model) {
            return $this->resolve($filter, $values, $model);
        })->reverse()->toArray();

        return $filter_detections;
    }
}
