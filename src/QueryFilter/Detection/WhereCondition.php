<?php


namespace eloquentFilter\QueryFilter\Detection;


class WhereCondition implements Detector
{
    public function detect($field, $params)
    {
        if (!empty($params)) {
            return 'where';
        }
    }
}
