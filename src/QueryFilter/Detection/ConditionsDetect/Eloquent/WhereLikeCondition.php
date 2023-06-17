<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereLike;

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
            $method = 'WhereLike';
        }

        return $method ?? null;
    }
}
