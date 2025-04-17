<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Detection\FieldsDetect\Contracts\DetectFieldsContract;
use eloquentFilter\QueryFilter\Queries\Base\BaseQuery;
use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;


/**
 * Class FuzzyCondition.
 */
class FuzzyCondition implements DefaultConditionsContract
{
    /**
     * @param $field
     * @param $value
     * @param $type
     * @param $filter
     * @param DetectFieldsContract|null $detectFields
     * @return string
     */
    public static function detect($field, $params): ?string
    {
        if (isset($params['fuzzy'])) {
            $method = 'Fuzzy';
        }

        return $method ?? null;
    }

    /**
     * @param $field
     * @param $value
     * @param $type
     * @param $filter
     * @param DetectFieldsContract|null $detectFields
     * @return BaseQuery
     */
    public function buildQuery($field, $value, $type, $filter, DetectFieldsContract $detectFields = null): BaseQuery
    {
        return new \eloquentFilter\QueryFilter\Queries\DB\Fuzzy($field, $value, $type);
    }
} 