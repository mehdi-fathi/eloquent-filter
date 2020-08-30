<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereOr;

/**
 * Class WhereOrCondition.
 */
class WhereOrCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_overide_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_overide_method = false): ?string
    {
        if ($field == 'or') {
            $method = WhereOr::class;
        }

        return $method ?? null;
    }
}
