<?php

namespace eloquentFilter\QueryFilter\Detection\DetectionFactory;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\DetectorDbCondition;

/**
 * Class DetectionDbFactory.
 */
class DetectionDbFactory implements DetectorFactoryContract
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
        $detect = app(DetectorDbCondition::class, ['detector' => $this->detections]);

        /** @see DetectorDbCondition::detect() */
        $method = $detect->detect($field, $params, $model);

        return $method;
    }
}
