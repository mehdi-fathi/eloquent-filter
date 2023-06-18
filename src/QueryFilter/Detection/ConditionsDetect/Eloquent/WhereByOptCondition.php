<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

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
