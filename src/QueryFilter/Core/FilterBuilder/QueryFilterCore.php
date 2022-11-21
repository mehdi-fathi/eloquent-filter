<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Detection\DetectionFactory;

interface QueryFilterCore
{
    public function __construct(array $default_injected, array $detect_injected = null);

    public function getDetectorFactory(array $default_detect = null, array $detect_injected = null);

    public function setDefaultDetect($default_detect): void;

    public function getDefaultDetect();

    public function setDetections(array $detections): void;

    public function setDetectFactory(DetectionFactory $detect_factory): void;

    public function getDetectFactory(): DetectionFactory;

    public function getDetections();

    public function setDetectInjected($detect_injected): void;

    public function getDetectInjected();
}
