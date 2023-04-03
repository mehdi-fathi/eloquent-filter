<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereLike;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (!empty($params['like'])) {
            $method = WhereLike::class;
        }

        return $method ?? null;
    }
}
