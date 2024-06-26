<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\core;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereCondition;
use eloquentFilter\QueryFilter\Detection\Contract\DetectorFactoryContract;
use eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract;
use eloquentFilter\QueryFilter\Detection\DetectionFactory\DetectionDbFactory;
use eloquentFilter\QueryFilter\Detection\DetectionFactory\DetectionFactory;

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
    private DetectorFactoryContract $detect_factory;

    protected MainBuilderConditionsContract $mainBuilderConditions;

    /**
     * QueryFilterCoreBuilder constructor.
     *
     * @param array $defaultSeriesInjected
     * @param array|null $detectInjected
     * @param \eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract $mainBuilderConditions
     */
    public function __construct(array $defaultSeriesInjected, array $detectInjected = null, MainBuilderConditionsContract $mainBuilderConditions)
    {
        if (!empty($detectInjected)) {
            $this->setInjectedDetections($detectInjected);
        }

        $this->setDefaultDetect($defaultSeriesInjected);

        if ($mainBuilderConditions->getName() == DBBuilderQueryByCondition::NAME) {

            $factories = $this->getDbDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections());

        } else {
            $factories = $this->getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections());

        }

        $this->setDetectFactory($factories);

        $this->mainBuilderConditions = $mainBuilderConditions;
    }

    /**
     * @return \eloquentFilter\QueryFilter\Detection\Contract\MainBuilderConditionsContract
     */
    public function getMainBuilderConditions(): MainBuilderConditionsContract
    {
        return $this->mainBuilderConditions;
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
    public function setDetectFactory(DetectorFactoryContract $detect_factory): void
    {
        $this->detect_factory = $detect_factory;
    }

    /**
     * @return DetectionFactory
     */
    public function getDetectFactory(): DetectorFactoryContract
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
     * @param mixed $detectInjected
     */
    public function setInjectedDetections($detectInjected): void
    {
        if (!config('eloquentFilter.enabled_custom_detection')) {
            return;
        }
        $this->injected_detections = $detectInjected;
    }

    /**
     * @return mixed
     */
    public function getInjectedDetections(): mixed
    {
        return $this->injected_detections;
    }

    /**
     * @param array|null $default_detect
     * @param array|null $detectInjected
     *
     * @return DetectionFactory
     */
    public function getDetectorFactory(array $default_detect = null, array $detectInjected = null): DetectorFactoryContract
    {
        $detections = $default_detect;

        if (!empty($detectInjected)) {
            $detections = array_merge($detectInjected, $default_detect);
        }

        $this->setDetections($detections);

        return app(DetectionFactory::class, ['detections' => $this->getDetections()]);
    }

    /**
     * @param array|null $default_detect
     * @param array|null $detectInjected
     *
     * @return \eloquentFilter\QueryFilter\Detection\DetectionFactory\DetectionDbFactory
     */
    public function getDbDetectorFactory(array $default_detect = null, array $detectInjected = null): DetectorFactoryContract
    {
        $detections = $default_detect;

        if (!empty($detectInjected)) {
            $detections = array_merge($detectInjected, $default_detect);
        }
        $this->setDetections($detections);

        return app(DetectionDbFactory::class, ['detections' => $this->getDetections()]);
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

    /**
     * @param array|null $injected_detections
     * @return void
     */
    public function setDetectionsDbInjected(?array $injected_detections): void
    {
        if (!empty($injected_detections)) {
            $this->setInjectedDetections($injected_detections);
            $this->setDetectFactory($this->getDbDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections()));
        }
    }

}
