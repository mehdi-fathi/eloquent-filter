<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\core;

use eloquentFilter\QueryFilter\Detection\DetectionFactory;

/**
 * Class QueryFilterCoreBuilder.
 */
class QueryFilterCoreBuilder implements QueryFilterCore
{
    /**
     * @var
     */
    protected $builder;

    /**
     * @var
     */
    protected array $detections;

    /**
     * @var
     */
    protected $detect_injected;

    /**
     * @var array
     */
    protected array $default_detect;

    /**
     * @var DetectionFactory
     */
    private DetectionFactory $detect_factory;

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
