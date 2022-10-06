<?php

namespace eloquentFilter\QueryFilter\Core;

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
     * @var
     */
    private $request;
    /**
     * @var
     */
    private $detect_factory;

    /**
     * ResolverDetections constructor.
     * @param $builder
     * @param $request
     * @param $detect_factory
     */
    public function __construct($builder, $request, $detect_factory)
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
     * @throws ReflectionException
     *
     * @return Application|mixed
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
