<?php

namespace eloquentFilter\QueryFilter\Detection;


/**
 * Interface Detector
 * @package eloquentFilter\QueryFilter\Detection
 */
interface Detector
{
    /**
     * @param $field
     * @param $params
     * @return mixed
     */
    public function detect($field, $params);
}
