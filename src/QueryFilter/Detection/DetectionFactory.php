<?php

namespace eloquentFilter\QueryFilter\Detection;

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
     *
     * @throws \ReflectionException
     *
     * @return mixed|string|null
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
