<?php

namespace eloquentFilter\QueryFilter\Detection\Contract;

/**
 * Interface DetectorConditionsContract.
 */
interface DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string;
}
