<?php

namespace Tests\Models\CustomDetect;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;

/**
 * Class WhereCondition.
 */
class WhereRelationLikeCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        if (!empty($params['value']) && !empty($params['limit']) && !empty($params['email'])) {
            $method = WhereLikeRelation::class;
        }

        return $method ?? null;
    }
}
