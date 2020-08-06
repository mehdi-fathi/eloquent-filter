<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements DetectorContract
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
