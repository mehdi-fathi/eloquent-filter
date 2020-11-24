<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereBetween;

/**
 * Class WhereBetweenCondition.
 */
class WhereBetweenCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return mixed|string
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (isset($params['start']) && isset($params['end'])) {
            $method = WhereBetween::class;
        }

        return $method ?? null;
    }
}
