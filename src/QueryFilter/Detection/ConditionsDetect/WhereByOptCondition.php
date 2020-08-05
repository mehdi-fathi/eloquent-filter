<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

class WhereByOptCondition implements Detector
{
    public static function detect($field, $params)
    {
        if (!empty($params['operator']) && !empty($params['value'])) {
            $method = 'whereByOpt';

            return $method;
        }
    }
}
