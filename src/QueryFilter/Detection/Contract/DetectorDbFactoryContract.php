<?php

namespace eloquentFilter\QueryFilter\Detection\Contract;

/**
 * Interface Detector.
 */
interface DetectorDbFactoryContract
{
    /**
     * @param string $field
     * @param $params
     * @return string|null
     */
    public function buildDetections(string $field, $params): ?string;
}
