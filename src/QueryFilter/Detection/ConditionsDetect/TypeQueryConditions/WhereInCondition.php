<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Core\HelperFilter;
use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereIn;

/**
 * Class WhereInCondition.
 */
class WhereInCondition implements DefaultConditionsContract
{
    use HelperFilter;
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (is_array($params) && !HelperFilter::isAssoc($params) && !stripos($field, '.')) {
            $method = WhereIn::class;
        }

        return $method ?? null;
    }
}
