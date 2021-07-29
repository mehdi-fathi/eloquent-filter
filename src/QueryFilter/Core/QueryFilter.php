<?php

namespace eloquentFilter\QueryFilter\Core;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereCustomCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereOrCondition;
use eloquentFilter\QueryFilter\Detection\DetectionFactory;

/**
 * Class QueryFilter.
 */
class QueryFilter
{
    /**
     * @var
     */
    protected $request;
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
    protected $accept_request;

    /**
     * @var
     */
    protected $ignore_request;

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
     * @param array      $request
     * @param array|null $detect_injected
     */
    public function __construct(?array $request, array $detect_injected = null)
    {
        if (!empty($request)) {
            $this->setRequest($request);
        }
        if (!empty($detect_injected)) {
            $this->setDetectInjected($detect_injected);
        }

        $this->setDefaultDetect($this->__getDefaultDetectorsInstance());
        $this->setDetectFactory($this->getDetectorFactory($this->getDefaultDetect(), $this->getDetectInjected()));
    }

    /**
     * @return array
     */
    private function __getDefaultDetectorsInstance(): array
    {
        return [
            SpecialCondition::class,
            WhereCustomCondition::class,
            WhereBetweenCondition::class,
            WhereByOptCondition::class,
            WhereLikeCondition::class,
            WhereInCondition::class,
            WhereOrCondition::class,
            WhereHasCondition::class,
            WhereCondition::class,
        ];
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
    protected function getDetectorFactory(array $default_detect = null, array $detect_injected = null)
    {
        $detections = $default_detect;

        if (!empty($detect_injected)) {
            $detections = array_merge($detect_injected, $default_detect);
        }

        $this->setDetections($detections);

        return app(DetectionFactory::class, ['detections' => $this->getDetections()]);
    }
}
