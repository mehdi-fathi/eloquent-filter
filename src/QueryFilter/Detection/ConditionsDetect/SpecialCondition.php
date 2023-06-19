<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class SpecialCondition.
 */
class SpecialCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if ($field == 'f_params') {
            return 'Special';
        }

        return null;
    }
}
