<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereLike;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements DefaultConditionsContract
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
