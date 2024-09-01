<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class WhereDateCondition.
 */
class WhereDateCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (is_string($params) && \DateTime::createFromFormat('Y-m-d', $params) !== false) {
            $method = 'WhereDate';
        }

        return $method ?? null;
    }
}
