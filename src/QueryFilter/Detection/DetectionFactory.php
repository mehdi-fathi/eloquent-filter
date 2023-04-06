<?php

namespace eloquentFilter\QueryFilter\Detection;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;

/**
 * Class DetectionFactory.
 */
class DetectionFactory implements DetectorFactoryContract
{
    /**
     * DetectionFactory constructor.
     *
     * @param array $detections
     */
    public function __construct(public array $detections)
    {
    }

    /**
     * @param $field
     * @param $params
     * @param null $model
     *
     * @return string|null
     */
    public function buildDetections($field, $params, $model = null): ?string
    {
        $detect = app(DetectorCondition::class, ['detector' => $this->detections]);

        /** @see DetectorCondition::detect() */
        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
