<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

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
            $method = 'WhereHas';
        }

        return $method ?? null;
    }
}
