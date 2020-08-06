<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;
use eloquentFilter\QueryFilter\HelperFilter;

/**
 * Class WhereInCondition.
 */
class WhereInCondition implements Detector
{
    use HelperFilter;

    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if (is_array($params) && !self::isAssoc($params) && !stripos($field, '.')) {
            return 'whereIn';
        }
    }
}
