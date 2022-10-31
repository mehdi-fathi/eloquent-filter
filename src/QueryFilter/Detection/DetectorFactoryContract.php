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
    public static function detect($field, $params, $model ): ?string;
}
