<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereCustom;

/**
 * Class WhereCustomCondition.
 */
class WhereCustomCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params, bool $is_override_method = false): ?string
    {
        if ($is_override_method == true) {
            $method = WhereCustom::class;
        }

        return $method ?? null;
    }
}
