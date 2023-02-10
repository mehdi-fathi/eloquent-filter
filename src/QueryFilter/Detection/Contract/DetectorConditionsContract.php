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
     * @param bool $is_override_method
     *
     * @return mixed
     */
    public static function detect($field, $params, bool $is_override_method = false): ?string;
}
