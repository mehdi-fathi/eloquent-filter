<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;

/**
 * Class WhereByOptCondition.
 */
class WhereByOptCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if (!empty($params['operator']) && !empty($params['value'])) {
            $method = 'whereByOpt';

            return $method;
        }
    }
}
