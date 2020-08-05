<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

class WhereCondition implements Detector
{
    public static function detect($field, $params)
    {
        if (!empty($params)) {
            return 'where';
        }
    }
}
