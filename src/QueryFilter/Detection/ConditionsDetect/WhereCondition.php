<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\Where;

/**
 * Class WhereCondition.
 */
class WhereCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (isset($params)) {
            $method = Where::class;
        }

        return $method ?? null;
    }
}
