<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereBetween;

/**
 * Class WhereBetweenCondition.
 */
class WhereBetweenCondition implements DetectorConditionsContract
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
            $method = WhereBetween::class;
        }

        return $method ?? null;
    }
}
