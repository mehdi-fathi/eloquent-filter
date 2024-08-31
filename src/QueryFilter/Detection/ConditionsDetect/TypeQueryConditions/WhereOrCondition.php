<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereOr;

/**
 * Class WhereOrCondition.
 */
class WhereOrCondition implements DefaultConditionsContract
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
