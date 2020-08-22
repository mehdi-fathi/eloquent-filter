<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\Queries\WhereLike;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereLikeCondition.
 */
class WhereLikeCondition implements DetectorContract
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
        if (!empty($params['like'])) {
            $method = WhereLike::class;
        }

        return $method ?? null;
    }
}
