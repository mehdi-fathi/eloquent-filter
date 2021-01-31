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
    public static $detections;

    /**
     * DetectionFactory constructor.
     *
     * @param $detections
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
     * @throws \ReflectionException
     *
     * @return mixed|string|null
     */
    public static function detect($field, $params, $model = null): ?string
    {
        $detect = app(DetectorConditions::class, ['detector' => self::$detections]);

        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
