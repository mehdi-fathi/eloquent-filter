<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereHas;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (stripos($field, '.')) {
            $method = 'WhereHas';
        }

        return $method ?? null;
    }
}
