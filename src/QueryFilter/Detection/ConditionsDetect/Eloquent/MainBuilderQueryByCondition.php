<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Queries\Special;
use eloquentFilter\QueryFilter\Queries\Where;
use eloquentFilter\QueryFilter\Queries\WhereBetween;
use eloquentFilter\QueryFilter\Queries\WhereByOpt;
use eloquentFilter\QueryFilter\Queries\WhereCustom;
use eloquentFilter\QueryFilter\Queries\WhereDate;
use eloquentFilter\QueryFilter\Queries\WhereHas;
use eloquentFilter\QueryFilter\Queries\WhereIn;
use eloquentFilter\QueryFilter\Queries\WhereLike;
use eloquentFilter\QueryFilter\Queries\WhereOr;

/**
 * Class MainBuilderQueryByCondition.
 */
class MainBuilderQueryByCondition implements MainBuilderConditionsContract
{
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
