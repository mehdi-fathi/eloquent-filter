<?php


namespace eloquentFilter\QueryFilter\Detection;


class WhereOrCondition implements Detector
{
    public function detect($field, $params)
    {
        if ($field == 'or') {
            return 'orWhere';
        }
    }
}
