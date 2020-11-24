<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereByOpt;

/**
 * Class WhereByOptCondition.
 */
class WhereByOptCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return mixed|string
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['operator']) && isset($params['value'])) {
            $method = WhereByOpt::class;
        }

        return $method ?? null;
    }
}
