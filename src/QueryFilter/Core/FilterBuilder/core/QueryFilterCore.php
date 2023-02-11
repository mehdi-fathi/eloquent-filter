<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\core;

use eloquentFilter\QueryFilter\Detection\DetectionFactory;

/**
 *
 */
interface QueryFilterCore
{
    /**
     * @param array $default_injected
     * @param array|null $detect_injected
     */
    public function __construct(array $default_injected, array $detect_injected = null);

    /**
     * @param array|null $default_detect
     * @param array|null $detect_injected
     * @return mixed
     */
    public function getDetectorFactory(array $default_detect = null, array $detect_injected = null);

    /**
     * @param $default_detect
     * @return void
     */
    public function setDefaultDetect($default_detect): void;

    /**
     * @return mixed
     */
    public function getDefaultDetect();

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
    public function getDetections();

    /**
     * @param $injected_detections
     * @return void
     */
    public function setInjectedDetections($injected_detections): void;

    /**
     * @return mixed
     */
    public function getInjectedDetections();
}
