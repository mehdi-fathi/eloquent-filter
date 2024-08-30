<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\Special;
use eloquentFilter\QueryFilter\Queries\Eloquent\Where;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereBetween;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereByOpt;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereCustom;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereDate;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereDoesntHave;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereHas;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereIn;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereLike;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereOr;

/**
 * Class MainBuilderQueryByCondition.
 */
class MainBuilderQueryByCondition implements MainBuilderConditionsContract
{
    const NAME = 'EloquentBuilder';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param $condition
     * @return string|null
     */
    public function build($condition): ?string
    {
        $builder = match ($condition) {
            'Where' => Where::class,
            'WhereBetween' => WhereBetween::class,
            'WhereByOpt' => WhereByOpt::class,
            'WhereDate' => WhereDate::class,
            'WhereHas' => WhereHas::class,
            'WhereIn' => WhereIn::class,
            'WhereLike' => WhereLike::class,
            'WhereOr' => WhereOr::class,
            'WhereDoesnt' => WhereDoesntHave::class,
            'Special' => Special::class,
            'WhereCustom' => WhereCustom::class,
            default => null,
        };

        if (empty($builder) && !empty($condition)) {
            return $condition;
        }

        return $builder;
    }
}
