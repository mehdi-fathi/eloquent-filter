<?php

namespace eloquentFilter\QueryFilter\Detection;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface Detector.
 */
interface DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param Model|null $model
     *
     * @return mixed
     */
    public static function detect($field, $params, $is_override_method = false): ?string;
}
