<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

/**
 * Class WhereBetweenCondition.
 */
class WhereBetweenCondition implements Detector
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if (!empty($params['start']) && !empty($params['end'])) {
            $method = 'whereBetween';

            return $method;
        }
    }
}
