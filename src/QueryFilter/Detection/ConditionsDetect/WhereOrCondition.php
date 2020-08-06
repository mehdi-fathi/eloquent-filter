<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

/**
 * Class WhereOrCondition
 * @package eloquentFilter\QueryFilter\Detection\ConditionsDetect
 */
class WhereOrCondition implements Detector
{
    /**
     * @param $field
     * @param $params
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if ($field == 'or') {
            return 'orWhere';
        }
    }
}
