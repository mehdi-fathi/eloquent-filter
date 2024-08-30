<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereDoesntHave;

/**
 * Class WhereCondition.
 */
class WhereDoesntHaveCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if ($field == 'doesnt_have') {
            $method = WhereDoesntHave::class;
        }

        return $method ?? null;
    }
}
