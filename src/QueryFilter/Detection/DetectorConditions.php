<?php

namespace eloquentFilter\QueryFilter\Detection;

class DetectorConditions
{
    private $detector;

    public function __construct(array $detector)
    {
        foreach ($detector as $detector_obj) {
            if ($detector_obj instanceof Detector) {
                $this->detector[] = $detector_obj;
            }
        }
    }

    public function detect($field, $params)
    {
        foreach ($this->detector as $detector_obj) {
            $out = $detector_obj->detect($field, $params);
            if (!empty($out)) {
                return $out;
            }
        }
    }
}
