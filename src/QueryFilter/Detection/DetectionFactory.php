<?php

namespace eloquentFilter\QueryFilter\Detection;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetectionFactory.
 */
class DetectionFactory implements DetectorContract
{
    /**
     * @var
     */
    public static $detectors;

    /**
     * DetectionFactory constructor.
     *
     * @param $detector
     */
    public function __construct($detector)
    {
        self::$detectors = $detector;
    }

    /**
     * @param $field
     * @param $params
     * @param Model|null $model
     *
     * @throws \ReflectionException
     *
     * @return mixed|string|null
     */
    public static function detect($field, $params, Model $model = null)
    {
        $detect = new DetectorConditions(
            self::$detectors
        );

        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
