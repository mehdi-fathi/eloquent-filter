<?php

namespace eloquentFilter\QueryFilter\Detection\DetectionFactory;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorDbFactoryContract;
use eloquentFilter\QueryFilter\Detection\Detector\DetectorConditionDbCondition;

/**
 * Class DetectionDbFactory.
 */
class DetectionDbFactory implements DetectorDbFactoryContract
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
     *
     * @return string|null
     */
    public function buildDetections($field, $params): ?string
    {
        $detect = app(DetectorConditionDbCondition::class, ['detector' => $this->detections]);

        /** @see DetectorConditionDbCondition::detect() */
        $method = $detect->detect($field, $params);

        return $method;
    }
}
