<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\ConditionsContract;
use eloquentFilter\QueryFilter\Queries\DB\WhereDay;
use eloquentFilter\QueryFilter\Queries\DB\WhereDayQuery;

/**
 * Class WhereDayCondition.
 */
class WhereDayCondition implements ConditionsContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['day'])) {
            $method = WhereDayQuery::class;
        }

        return $method ?? null;
    }
} 