<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\Special;

/**
 * Class SpecialCondition.
 */
class SpecialCondition implements DefaultConditionsContract
{
    const SPECIAL_PARAM_NAME = 'f_params';

    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if ($field == self::SPECIAL_PARAM_NAME) {
            return Special::class;
        }

        return null;
    }
}
