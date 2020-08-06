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
     *
     * @return mixed
     */
    public static function detect($field, $params);
}
