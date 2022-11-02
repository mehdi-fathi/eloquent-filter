<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereDate;

/**
 * Class WhereCondition.
 */
class WhereDateCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     * @param bool $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, bool $is_override_method = false): ?string
    {
        if (is_string($params) && \DateTime::createFromFormat('Y-m-d', $params) !== false) {
            $method = WhereDate::class;
        }

        return $method ?? null;
    }
}
