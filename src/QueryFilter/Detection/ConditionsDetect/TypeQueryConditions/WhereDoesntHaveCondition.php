<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class WhereDoesntHaveCondition.
 */
class WhereDoesntHaveCondition implements DefaultConditionsContract
{
    const PARAM_NAME = 'doesnt_have';
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if ($field == self::PARAM_NAME) {
            $method = 'WhereDoesntHave';
        }

        return $method ?? null;
    }
}
