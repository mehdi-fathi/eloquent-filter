<?php


namespace eloquentFilter\QueryFilter\Detection;


interface Detector
{
    public function detect($field, $params);
}
