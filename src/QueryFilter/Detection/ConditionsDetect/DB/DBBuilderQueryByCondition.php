<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB;

use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;

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
            'Where' => \eloquentFilter\QueryFilter\Queries\DB\Where::class,
            // 'WhereBetween' => WhereBetween::class,
            // 'WhereByOpt' => WhereByOpt::class,
            // 'WhereDate' => WhereDate::class,
            // 'WhereHas' => WhereHas::class,
            // 'WhereIn' => WhereIn::class,
            // 'WhereLike' => WhereLike::class,
            // 'WhereOr' => WhereOr::class,
            // 'Special' => Special::class,
            // 'WhereCustom' => WhereCustom::class,
            // default => null,
        };

        if (empty($builder) && !empty($condition)) {
            return $condition;
        }

        return $builder;
    }
}
