<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereBetween;

/**
 * Class WhereBetweenCondition.
 */
class WhereBetweenCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params): ?string
    {
        if (isset($params['start']) && isset($params['end'])) {
            $method = 'WhereBetween';
        }

        return $method ?? null;
    }
}
