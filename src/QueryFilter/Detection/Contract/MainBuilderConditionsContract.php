<?php

namespace eloquentFilter\QueryFilter\Detection\Contract;

/**
 * Interface DetectorConditionsContract.
 */
interface MainBuilderConditionsContract
{
    /**
     * @param $condition
     * @return string|null
     */
    public function build($condition): ?string;
}
