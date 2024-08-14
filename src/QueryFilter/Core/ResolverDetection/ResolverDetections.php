<?php

namespace eloquentFilter\QueryFilter\Core\ResolverDetection;

use eloquentFilter\QueryFilter\Core\FilterBuilder\MainQueryFilterBuilder;
use eloquentFilter\QueryFilter\Core\ReflectionException;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Detection\DetectionFactory\DetectionDbFactory;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;

/**
 * Class ResolverDetections
 * @package eloquentFilter\QueryFilter\Core
 */
abstract class ResolverDetections
{
    /**
     * @var
     */
    protected $builder;
    /**
     * @var array
     */
    protected array $request;
    /**
     * @var \eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract
     */
    protected DetectorFactoryContract $detector_factory;
    protected DetectionDbFactory $detector_db_factory;

    protected MainBuilderConditionsContract $main_builder_conditions;


    /**
     * @return mixed
     * @see MainQueryFilterBuilder
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
     * @return array
     */
    abstract public function getFiltersDetection(): array;
}
