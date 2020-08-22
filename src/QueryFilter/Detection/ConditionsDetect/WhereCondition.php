<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\Where;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereCondition.
 */
class WhereCondition implements DetectorContract
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
        if (!empty($params)) {
            $method = Where::class;
        }
        return $method ?? null;

    }
}
