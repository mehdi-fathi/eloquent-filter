<?php

namespace eloquentFilter\QueryFilter\Detection;

class WhereByOptCondition implements Detector
{
    public function detect($field, $params)
    {
        if (!empty($params['operator']) && !empty($params['value'])) {
            $method = 'whereByOpt';

            return $method;
        }
    }
}
