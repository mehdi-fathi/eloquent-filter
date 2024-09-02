<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class WhereOrCondition.
 */
class WhereOrCondition implements DefaultConditionsContract
{

    const PARAM_NAME = 'or';

    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if ($field == self::PARAM_NAME) {
            $method = 'WhereOr';
        }

        return $method ?? null;
    }
}
