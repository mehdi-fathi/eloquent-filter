<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

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
            return 'WhereIn';
        }
        if (isset($params['in']) && is_array($params['in'])) {
            return 'WhereIn';
        } elseif (isset($params['not_in']) && is_array($params['not_in'])) {
            return 'WhereNotIn';
        }

        return null;
    }
}
