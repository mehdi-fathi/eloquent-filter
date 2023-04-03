<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereOr;

/**
 * Class WhereOrCondition.
 */
class WhereOrCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if ($field == 'or') {
            $method = WhereOr::class;
        }

        return $method ?? null;
    }
}
