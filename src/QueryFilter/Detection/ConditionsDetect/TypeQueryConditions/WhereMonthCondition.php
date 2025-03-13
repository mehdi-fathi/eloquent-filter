<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\ConditionsContract;
use eloquentFilter\QueryFilter\Queries\DB\WhereMonth;
use eloquentFilter\QueryFilter\Queries\DB\WhereMonthQuery;

/**
 * Class WhereMonthCondition.
 */
class WhereMonthCondition implements ConditionsContract
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
        if (!empty($params['month'])) {
            $method = WhereMonthQuery::class;
        }

        return $method ?? null;
    }
} 