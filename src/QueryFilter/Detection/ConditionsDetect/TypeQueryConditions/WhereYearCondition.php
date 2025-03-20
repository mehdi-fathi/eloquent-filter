<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\ConditionsContract;
use eloquentFilter\QueryFilter\Queries\DB\WhereYear;
use eloquentFilter\QueryFilter\Queries\DB\WhereYearQuery;

/**
 * Class WhereYearCondition.
 */
class WhereYearCondition implements ConditionsContract
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
        if (!empty($params['year'])) {
            $method = WhereYearQuery::class;
        }

        return $method ?? null;
    }
} 