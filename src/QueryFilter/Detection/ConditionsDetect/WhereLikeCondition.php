<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereLike;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements DetectorContract
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
