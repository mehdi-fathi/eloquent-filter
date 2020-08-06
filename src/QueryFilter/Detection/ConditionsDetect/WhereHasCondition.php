<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\Detector;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements Detector
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if (stripos($field, '.')) {
            return 'wherehas';
        }
    }
}
