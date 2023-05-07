<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\core;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereCondition;
use eloquentFilter\QueryFilter\Detection\DetectionFactory;

/**
 * Class QueryFilterCoreBuilder.
 */
class QueryFilterCoreBuilder implements QueryFilterCore
{
    /**
     * @var array
     */
    protected array $detections;

    /**
     * @var
     */
    protected $injected_detections;

    /**
     * @var array
     */
    protected array $default_detect;

    /**
     * @var DetectionFactory
     */
    private DetectionFactory $detect_factory;

    /**
     * QueryFilterCoreBuilder constructor.
     *
     * @param array $default_injected
     * @param array|null $detect_injected
     */
    public function __construct(array $default_injected, array $detect_injected = null)
    {
        if (!empty($detect_injected)) {
            $this->setInjectedDetections($detect_injected);
        }

        $this->setDefaultDetect($default_injected);
        $this->setDetectFactory($this->getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections()));
    }

    /**
     * @param mixed $default_detect
     */
    public function setDefaultDetect($default_detect): void
    {
        $this->default_detect = $default_detect;
    }

    /**
     * @return array
     */
    public function getDefaultDetect(): array
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
     * @param array|null $detections
     */
    public function unsetDetection(?array $detections): void
    {
        if (is_array($detections)) {
            $detections = array_map(function ($item) {
                $class = new \ReflectionClass(WhereCondition::class);
                return $class->getNamespaceName() . '\\' . $item;
            }, $detections);

            $array = array_diff($this->getDefaultDetect(), $detections);

            $this->setDefaultDetect($array);
        }
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
    public function getDetections(): array
    {
        return $this->detections;
    }

    /**
     * @param mixed $detect_injected
     */
    public function setInjectedDetections($detect_injected): void
    {
        if (!config('eloquentFilter.enabled_custom_detection')) {
            return;
        }
        $this->injected_detections = $detect_injected;
    }

    /**
     * @return mixed
     */
    public function getInjectedDetections()
    {
        return $this->injected_detections;
    }

    /**
     * @param array|null $default_detect
     * @param array|null $detect_injected
     *
     * @return DetectionFactory
     */
    public function getDetectorFactory(array $default_detect = null, array $detect_injected = null): DetectionFactory
    {
        $detections = $default_detect;

        if (!empty($detect_injected)) {
            $detections = array_merge($detect_injected, $default_detect);
        }

        $this->setDetections($detections);

        return app(DetectionFactory::class, ['detections' => $this->getDetections()]);
    }

    /**
     * @param array|null $injected_detections
     * @return void
     */
    public function setDetectionsInjected(?array $injected_detections): void
    {
        if (!empty($injected_detections)) {
            $this->setInjectedDetections($injected_detections);
            $this->setDetectFactory($this->getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections()));
        }
    }

    /**
     * @return void
     */
    public function reload(): void
    {
        $this->setDetectFactory($this->getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections()));
    }
}
