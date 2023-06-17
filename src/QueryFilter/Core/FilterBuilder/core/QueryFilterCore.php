<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\core;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\MainBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Detection\DetectionFactory;

/**
 *
 */
interface QueryFilterCore
{
    /**
     * @param array $defaultSeriesInjected
     * @param array|null $detectInjected
     */
    public function __construct(array $defaultSeriesInjected, array $detectInjected = null,MainBuilderConditionsContract $mainBuilderConditions);

    /**
     * @param array|null $default_detect
     * @param array|null $detectInjected
     * @return mixed
     */
    public function getDetectorFactory(array $default_detect = null, array $detectInjected = null): DetectionFactory;

    /**
     * @param $default_detect
     * @return void
     */
    public function setDefaultDetect($default_detect): void;

    /**
     * @return mixed
     */
    public function getDefaultDetect(): array;

    /**
     * @param array $detections
     * @return void
     */
    public function setDetections(array $detections): void;

    /**
     * @param \eloquentFilter\QueryFilter\Detection\DetectionFactory $detect_factory
     * @return void
     */
    public function setDetectFactory(DetectionFactory $detect_factory): void;

    /**
     * @return \eloquentFilter\QueryFilter\Detection\DetectionFactory
     */
    public function getDetectFactory(): DetectionFactory;

    /**
     * @return mixed
     */
    public function getDetections(): array;

    /**
     * @param $injected_detections
     * @return void
     */
    public function setInjectedDetections($injected_detections): void;

    /**
     * @return mixed
     */
    public function getInjectedDetections(): mixed;
}
