<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Queries\WhereIn;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WhereInCondition.
 */
class WhereInCondition implements DetectorContract
{
    use HelperFilter;


    /**
     * @param $field
     * @param $params
     * @param $is_overide_method
     * @return string|null
     */
    public static function detect($field, $params, $is_overide_method = false): ?string
    {
        if (is_array($params) && !self::isAssoc($params) && !stripos($field, '.')) {
            $method = WhereIn::class;
        }

        return $method ?? null;
    }
}
