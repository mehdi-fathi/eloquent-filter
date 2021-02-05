<?php

namespace eloquentFilter\QueryFilter\Detection;

/**
 * Interface Detector.
 */
interface DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param bool $is_override_method
     *
     * @return mixed
     */
    public static function detect($field, $params, $is_override_method = false): ?string;
}
