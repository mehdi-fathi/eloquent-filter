<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereBetween;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereBetweenCondition.
 */
class WhereBetweenCondition implements DetectorContract
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
        if (!empty($params['start']) && !empty($params['end'])) {
            $method = WhereBetween::class;
        }


        return $method ?? null;
    }
}
