<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

class WhereLikeCondition implements Detector
{
    public static function detect($field, $params)
    {
        if (!empty($params['like'])) {
            return 'like';
        }
    }
}
