<?php


namespace eloquentFilter\QueryFilter\Detection;


class WhereHasCondition implements Detector
{
    public function detect($field, $params)
    {
        if (stripos($field, '.')) {
            return 'wherehas';
        }
    }
}
