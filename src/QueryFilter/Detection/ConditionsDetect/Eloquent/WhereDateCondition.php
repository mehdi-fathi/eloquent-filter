<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereDate;

/**
 * Class WhereCondition.
 */
class WhereDateCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (is_string($params) && \DateTime::createFromFormat('Y-m-d', $params) !== false) {
            $method = WhereDate::class;
        }

        return $method ?? null;
    }
}
