<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Core\HelperFilter;
use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

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
            $method = 'WhereIn';
        }

        return $method ?? null;
    }
}
