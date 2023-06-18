<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class WhereCondition.
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
