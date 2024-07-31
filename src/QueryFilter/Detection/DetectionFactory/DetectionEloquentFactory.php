<?php

namespace eloquentFilter\QueryFilter\Detection\DetectionFactory;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Detector\DetectorConditionCondition;

/**
 * Class DetectionEloquentFactory.
 */
class DetectionEloquentFactory implements DetectorFactoryContract
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
        $detect = app(DetectorConditionCondition::class, ['detector' => $this->detections]);

        $class_name = null;
        if (!empty($model)) {
            $class_name = class_basename($model);
        }

        /** @see DetectorConditionCondition::detect() */
        $method = $detect->detect($field, $params, $model->getWhiteListFilter(), $model->checkModelHasOverrideMethod($field), $class_name);

        return $method;
    }
}
