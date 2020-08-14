<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereHas;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param Model|null $model
     *
     * @return mixed|string
     */
    public static function detect($field, $params, Model $model = null)
    {
        if (stripos($field, '.')) {
            return WhereHas::class;
        }
    }
}
