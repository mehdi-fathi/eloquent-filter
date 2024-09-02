<?php

namespace eloquentFilter\QueryFilter\Detection\Contract;


/**
 * Interface DetectorConditionsContract.
 */
interface DetectorConditionContract
{
    /**
     * DetectorConditions constructor.
     *
     * @param array $detector
     */
    public function __construct(array $detector);

    /**
     * @param \Illuminate\Support\Collection $detector
     */
    public function setDetector(\Illuminate\Support\Collection $detector): void;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDetector(): \Illuminate\Support\Collection;

    /**
     * @param string $field
     * @param $params
     * @param null $getWhiteListFilter
     * @param bool $hasOverrideMethod
     * @param $className
     * @return string|null
     * @throws \eloquentFilter\QueryFilter\Detection\Contract\Exception
     */
    public function detect(string $field, $params, $getWhiteListFilter = null, bool $hasOverrideMethod = false, $className = null): ?string;

}
