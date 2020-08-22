<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Queries\Special;
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
     * @param Model|null $model
     *
     * @return mixed|string
     */
    public static function detect($field, $params, Model $model = null): ?string
    {
        if ($field == 'f_params') {
            return Special::class;
        }

        return null;
    }
}
