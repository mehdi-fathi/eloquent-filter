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

    /**
     * @var
     */
    protected $request;
    /**
     * @var
     */
    protected $builder;

    /**
     * @var DetectionFactory
     */
    protected $detect;

    /**
     * QueryFilter constructor.
     *
     * @param array $request
     */
    public function __construct(?array $request)
    {
        if (!empty($request)) {
            $this->setRequest($request);
        }
        $this->detect = $this->__getDetectorsInstanceArray();
    }

    /**
     * @param Builder    $builder
     * @param array|null $request
     * @param array|null $ignore_request
     *
     * @return Builder
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null): Builder
    {
        $this->builder = $builder;

        if (!empty($request)) {
            $this->setRequest($request);
        }
        $this->setFilterRequests($ignore_request, $this->builder->getModel());

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
     * @return DetectionFactory
     */
    private function __getDetectorsInstanceArray()
    {
        return
            new DetectionFactory(
                [
                    SpecialCondition::class,
                    WhereCustomCondition::class,
                    WhereBetweenCondition::class,
                    WhereByOptCondition::class,
                    WhereLikeCondition::class,
                    WhereInCondition::class,
                    WhereOrCondition::class,
                    WhereHasCondition::class,
                    WhereCondition::class,
                ]
            );
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
        $detect = $this->detect::detect($filterName, $values, $model);

        return app($detect, ['filter' => $filterName, 'values' => $values]);
    }
}
