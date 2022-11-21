<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Core\HelperFilter;
use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereIn;

/**
 * Class WhereInCondition.
 */
class WhereInCondition implements DetectorConditionsContract
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
        if (is_array($params) && !HelperFilter::isAssoc($params) && !stripos($field, '.')) {
            $method = WhereIn::class;
        }

        return $method ?? null;
    }
}
