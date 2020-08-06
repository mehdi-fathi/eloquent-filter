<?php

namespace eloquentFilter\QueryFilter\Detection;

/**
 * Class DetectionFactory
 * @package eloquentFilter\QueryFilter\Detection
 */
class DetectionFactory implements Detector
{
    /**
     * @var
     */
    public static $detectors;

    /**
     * DetectionFactory constructor.
     * @param $detector
     */
    public function __construct($detector)
    {
        self::$detectors = $detector;
    }

    /**
     * @param $field
     * @param $params
     * @return mixed|string|null
     * @throws \ReflectionException
     */
    public static function detect($field, $params)
    {
        $detect = new DetectorConditions(
            self::$detectors
        );

        $method = $detect->detect($field, $params);

        return $method;
    }
}
