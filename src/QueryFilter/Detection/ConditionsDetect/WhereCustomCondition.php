<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereCustom;
use eloquentFilter\QueryFilter\Queries\WhereOr;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereOrCondition.
 */
class WhereCustomCondition implements DetectorContract
{

    /**
     * @param $field
     * @param $params
     * @param Model|null $model
     * @return mixed|string
     */
    public static function detect($field, $params, Model $model = null)
    {
        if(self::isCustomFilter($model,$field)){
            return WhereCustom::class;

        }
    }

    /**
     * @param $query
     * @param $filterName
     * @return bool
     */
    private static function isCustomFilter($query, $filterName)
    {
        return method_exists($query->getmodel(), $filterName);
    }
}
