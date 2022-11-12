<?php

namespace eloquentFilter\QueryFilter\Detection;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetectionFactory.
 */
class DetectionFactory implements DetectorFactoryContract
{
    /**
     * @var array
     */
    public array $detections;

    /**
     * DetectionFactory constructor.
     *
     * @param array $detections
     */
    public function __construct(array $detections)
    {
        $this->detections = $detections;
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
    public function buildDetections($field, $params, $model = null): ?string
    {
        $detect = app(DetectorCondition::class, ['detector' => $this->detections]);

        /** @see DetectorCondition::detect() */
        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
