<?php

namespace eloquentFilter\QueryFilter\Detection\DetectionFactory;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Detector\DetectorConditionDbCondition;

/**
 * Class DetectionDbFactory.
 */
class DetectionDbFactory implements DetectorFactoryContract
{
    /**
     * DetectionDbFactory constructor.
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
        $detect = app(DetectorConditionDbCondition::class, ['detector' => $this->detections]);

        /** @see DetectorConditionDbCondition::detect() */
        $method = $detect->detect($field, $params, null);

        return $method;
    }
}
