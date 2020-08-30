<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect;

use eloquentFilter\QueryFilter\Detection\DetectorContract;
use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Queries\Special;

/**
 * Class WhereInCondition.
 */
class SpecialCondition implements DetectorContract
{
    use HelperFilter;

    /**
     * @param $field
     * @param $params
     * @param bool $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if ($field == 'f_params') {
            return Special::class;
        }

        return null;
    }
}
