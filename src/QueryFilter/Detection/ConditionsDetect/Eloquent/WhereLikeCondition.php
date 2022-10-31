<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereLike;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements DetectorConditionsContract
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
        if (!empty($params['like'])) {
            $method = WhereLike::class;
        }

        return $method ?? null;
    }
}
