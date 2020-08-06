<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;

/**
 * Class WhereOrCondition.
 */
class WhereOrCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params)
    {
        if ($field == 'or') {
            return 'orWhere';
        }
    }
}
