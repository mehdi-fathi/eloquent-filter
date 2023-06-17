<?php

namespace eloquentFilter\QueryFilter\Detection\Contract;

/**
 * Interface DetectorConditionsContract.
 */
interface ConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string;
}
