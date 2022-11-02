<?php

namespace eloquentFilter\QueryFilter\Detection;

/**
 * Interface Detector.
 */
interface DetectorFactoryContract
{
    /**
     * @param $field
     * @param $params
     * @param $model
     * @return mixed
     */
    public static function buildDetections($field, $params, $model): ?string;
}
