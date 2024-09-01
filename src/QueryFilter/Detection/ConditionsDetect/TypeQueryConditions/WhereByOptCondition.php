<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereByOpt;

/**
 * Class WhereByOptCondition.
 */
class WhereByOptCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return mixed|string
     */
    public static function detect($field, $params): ?string
    {
        if (!empty($params['operator']) && isset($params['value'])) {
            $method = 'WhereByOpt';
        }

        return $method ?? null;
    }
}
