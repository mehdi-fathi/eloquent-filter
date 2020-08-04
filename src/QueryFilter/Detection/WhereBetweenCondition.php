<?php


namespace eloquentFilter\QueryFilter\Detection;


class WhereBetweenCondition implements Detector
{
    public function detect($field, $params)
    {
        if (!empty($params['start']) && !empty($params['end'])) {
            $method = 'whereBetween';
            return $method;
        }
    }
}
