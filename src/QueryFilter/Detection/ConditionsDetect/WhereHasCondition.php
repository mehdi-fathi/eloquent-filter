<?php


namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

class WhereHasCondition implements Detector
{
    public function detect($field, $params)
    {
        if (stripos($field, '.')) {
            return 'wherehas';
        }
    }
}
