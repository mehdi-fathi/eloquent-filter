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
    //todo should change name later
    public static function detect($field, $params, $model = null): ?string
    {
        $detect = app(DetectorConditions::class, ['detector' => self::$detections]);

        /** @see DetectorConditions::detect() */
        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
