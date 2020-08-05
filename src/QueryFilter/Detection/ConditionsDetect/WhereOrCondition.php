<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

class WhereOrCondition implements Detector
{
    public static function detect($field, $params)
    {
        if ($field == 'or') {
            return 'orWhere';
        }
    }
}
