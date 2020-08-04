<?php


namespace eloquentFilter\QueryFilter\Detection;


use eloquentFilter\QueryFilter\HelperFilter;

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
