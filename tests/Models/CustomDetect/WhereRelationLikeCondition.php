<?php

namespace Tests\Models\CustomDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;

/**
 * Class WhereCondition.
 */
class WhereRelationLikeCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['value']) && !empty($params['limit']) && !empty($params['email'])) {
            $method = WhereLikeRelation::class;
        }

        return $method ?? null;
    }
}
