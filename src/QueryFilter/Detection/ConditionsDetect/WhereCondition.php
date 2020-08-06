<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

/**
 * Class WhereCondition
 * @package eloquentFilter\QueryFilter\Detection\ConditionsDetect
 */
class WhereCondition implements Detector
{
    /**
     * @param $field
     * @param $params
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if (!empty($params)) {
            return 'where';
        }
    }
}
