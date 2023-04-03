<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\Where;

/**
 * Class WhereCondition.
 */
class WhereCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (isset($params)) {
            $method = Where::class;
        }

        return $method ?? null;
    }
}
