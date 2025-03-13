<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class WhereNullCondition.
 */
class WhereNullCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (isset($params['null'])) {
            return 'WhereNull';
        } elseif (isset($params['not_null'])) {
            return 'WhereNotNull';
        }

        return null;
    }
} 