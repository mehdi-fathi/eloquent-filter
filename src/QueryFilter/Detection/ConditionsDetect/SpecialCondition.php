<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Queries\Limit;
use eloquentFilter\QueryFilter\Queries\OrderBy;
use eloquentFilter\QueryFilter\Queries\Special;
use eloquentFilter\QueryFilter\Queries\WhereIn;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereInCondition.
 */
class SpecialCondition implements DetectorContract
{
    use HelperFilter;

    /**
     * @param $field
     * @param $params
     *
     * @param Model|null $model
     * @return mixed|string
     */
    public static function detect($field, $params, Model $model = null)
    {
        if($field == 'f_params'){
            return Special::class;
        }
    }
}
