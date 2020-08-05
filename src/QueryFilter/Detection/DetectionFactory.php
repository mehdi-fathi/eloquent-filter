<?php

namespace eloquentFilter\QueryFilter\Detection;

class DetectionFactory implements Detector
{
    public static $detectors;

    public function __construct($detector)
    {
        self::$detectors = $detector;
    }

    public static function detect($field, $params)
    {
        $detect = new DetectorConditions(
            self::$detectors
        );

        $method = $detect->detect($field, $params);

        return $method;
    }
}
