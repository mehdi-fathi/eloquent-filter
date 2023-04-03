<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereByOpt;

/**
 * Class WhereByOptCondition.
 */
class WhereByOptCondition implements DetectorConditionsContract
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
            $method = WhereByOpt::class;
        }

        return $method ?? null;
    }
}
