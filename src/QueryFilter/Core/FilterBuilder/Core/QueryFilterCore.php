<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\Core;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;

/**
 *
 */
interface QueryFilterCore
{
    /**
     * @param array $defaultSeriesInjected
     * @param array|null $detectInjected
     * @param \eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract $mainBuilderConditions
     */
    public function __construct(array $defaultSeriesInjected, array $detectInjected = null, MainBuilderConditionsContract $mainBuilderConditions);

    /**
     * @return mixed
     */
    public function getDetectorFactory(): DetectorFactoryContract;

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
     * @param \eloquentFilter\QueryFilter\Detection\DetectionFactory\DetectionEloquentFactory $detect_factory
     * @return void
     */
    public function setDetectFactory(DetectorFactoryContract $detect_factory): void;

    /**
     * @return \eloquentFilter\QueryFilter\Detection\DetectionFactory\DetectionEloquentFactory
     */
    public function getDetectFactory(): DetectorFactoryContract;

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
