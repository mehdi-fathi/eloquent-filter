<?php


namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;


use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Detection\Detector;

class WhereInCondition implements Detector
{
    use HelperFilter;
    public function detect($field, $params)
    {
        if (is_array($params) && !$this->isAssoc($params) && !stripos($field, '.')) {
            return 'whereIn';
        }
    }
}
