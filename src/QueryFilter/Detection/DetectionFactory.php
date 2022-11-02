<?php

namespace eloquentFilter\QueryFilter\Detection;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetectionFactory.
 */
class DetectionFactory implements DetectorFactoryContract
{
    /**
     * @var
     */
    public static $detections;

    /**
     * DetectionFactory constructor.
     *
     * @param array $detections
     */
    public function __construct(array $detections)
    {
        self::$detections = $detections;
    }

    /**
     * @param $field
     * @param $params
     * @param Model|null $model
     *
     * @return mixed|string|null
     * @throws \ReflectionException
     *
     */
    public static function buildDetections($field, $params, $model = null): ?string
    {
        $detect = app(DetectorCondition::class, ['detector' => self::$detections]);

        /** @see DetectorCondition::detect() */
        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
