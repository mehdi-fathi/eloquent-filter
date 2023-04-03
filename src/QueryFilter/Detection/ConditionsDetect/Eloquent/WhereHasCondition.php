<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereHas;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements DetectorConditionsContract
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
            $method = WhereHas::class;
        }

        return $method ?? null;
    }
}
