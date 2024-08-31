<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereHas;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (stripos($field, '.')) {
            $method = WhereHas::class;
        }

        return $method ?? null;
    }
}
