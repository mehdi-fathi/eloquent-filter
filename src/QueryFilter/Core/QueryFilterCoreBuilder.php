<?php

namespace eloquentFilter\QueryFilter\Core;

use eloquentFilter\QueryFilter\Detection\DetectionFactory;
use eloquentFilter\QueryFilter\HelperEloquentFilter;
use eloquentFilter\QueryFilter\HelperFilter;

/**
 * Class EloquentQueryFilterCore.
 */
class QueryFilterCoreBuilder implements QueryFilterCore
{
    use HelperFilter;
    use HelperEloquentFilter;

    use IoTraitCore;

    /**
     * @var
     */
    public $request;
    /**
     * @var
     */
    protected $builder;

    /**
     * @var
     */
    protected $detections;

    /**
     * @var
     */
    protected $detect_injected;

    /**
     * @var
     */
    protected $default_detect;

    /**
     * @var DetectionFactory
     */
    private $detect_factory;

    /**
     * QueryFilter constructor.
     *
     * @param array $default_injected
     * @param array|null $detect_injected
     */
    public function __construct(array $default_injected, array $detect_injected = null)
    {
        if (!empty($detect_injected)) {
            $this->setDetectInjected($detect_injected);
        }

        $this->setDefaultDetect($default_injected);
        $this->setDetectFactory($this->getDetectorFactory($this->getDefaultDetect(), $this->getDetectInjected()));
    }

    /**
     * @param mixed $builder
     */
    public function setBuilder($builder): void
    {
        $this->builder = $builder;
    }

    /**
     * @return mixed
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @param mixed $default_detect
     */
    public function setDefaultDetect($default_detect): void
    {
        $this->default_detect = $default_detect;
    }

    /**
     * @return mixed
     */
    public function getDefaultDetect()
    {
        return $this->default_detect;
    }

    /**
     * @param array $detections
     */
    public function setDetections(array $detections): void
    {
        $this->detections = $detections;
    }

    /**
     * @param DetectionFactory $detect_factory
     */
    public function setDetectFactory(DetectionFactory $detect_factory): void
    {
        $this->detect_factory = $detect_factory;
    }

    /**
     * @return DetectionFactory
     */
    public function getDetectFactory(): DetectionFactory
    {
        return $this->detect_factory;
    }

    /**
     * @return array
     */
    public function getDetections()
    {
        return $this->detections;
    }

    /**
     * @param mixed $detect_injected
     */
    public function setDetectInjected($detect_injected): void
    {
        if (config('eloquentFilter.enabled_custom_detection') == false) {
            return;
        }
        $this->detect_injected = $detect_injected;
    }

    /**
     * @return mixed
     */
    public function getDetectInjected()
    {
        return $this->detect_injected;
    }

    /**
     * @param array|null $default_detect
     * @param array|null $detect_injected
     *
     * @return DetectionFactory
     */
    public function getDetectorFactory(array $default_detect = null, array $detect_injected = null)
    {
        $detections = $default_detect;

        if (!empty($detect_injected)) {
            $detections = array_merge($detect_injected, $default_detect);
        }

        $this->setDetections($detections);

        return app(DetectionFactory::class, ['detections' => $this->getDetections()]);
    }
}
