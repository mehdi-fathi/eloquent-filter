<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereByOpt;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereByOptCondition.
 */
class WhereByOptCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param Model|null $model
     *
     * @return mixed|string
     */
    public static function detect($field, $params, Model $model = null): ?string
    {
        if (!empty($params['operator']) && !empty($params['value'])) {
            $method = WhereByOpt::class;
        }

        return $method ?? null;
    }
}
