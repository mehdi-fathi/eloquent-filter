<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements Detector
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if (!empty($params['like'])) {
            return 'like';
        }
    }
}
