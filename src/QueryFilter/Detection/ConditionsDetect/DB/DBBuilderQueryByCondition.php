<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB;

use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Queries\DB\Special;
use eloquentFilter\QueryFilter\Queries\DB\Where;
use eloquentFilter\QueryFilter\Queries\DB\WhereBetween;
use eloquentFilter\QueryFilter\Queries\DB\WhereByOpt;
use eloquentFilter\QueryFilter\Queries\DB\WhereCustom;
use eloquentFilter\QueryFilter\Queries\DB\WhereDate;
use eloquentFilter\QueryFilter\Queries\DB\WhereIn;
use eloquentFilter\QueryFilter\Queries\DB\WhereLike;
use eloquentFilter\QueryFilter\Queries\DB\WhereOr;

/**
 * Class MainBuilderQueryByCondition.
 */
class DBBuilderQueryByCondition implements MainBuilderConditionsContract
{
    private string $name = 'DbBuilder';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
            // 'WhereHas' => WhereHas::class,
            'WhereIn' => WhereIn::class,
            'WhereLike' => WhereLike::class,
            'WhereOr' => WhereOr::class,
            'Special' => Special::class,
            // 'WhereCustom' => WhereCustom::class,
            default => null,
        };

        if (empty($builder) && !empty($condition)) {
            return $condition;
        }

        return $builder;
    }
}
