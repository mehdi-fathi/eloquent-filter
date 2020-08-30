<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereHas;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param bool $is_overide_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_overide_method = false): ?string
    {
        if (stripos($field, '.')) {
            $method = WhereHas::class;
        }

        return $method ?? null;
    }
}
