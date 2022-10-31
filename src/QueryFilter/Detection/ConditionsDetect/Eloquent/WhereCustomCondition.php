<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereCustom;

/**
 * Class WhereOrCondition.
 */
class WhereCustomCondition implements DetectorConditionsContract
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
        if ($is_override_method == true) {
            $method = WhereCustom::class;
        }

        return $method ?? null;
    }
}
