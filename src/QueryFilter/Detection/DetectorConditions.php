<?php

namespace eloquentFilter\QueryFilter\Detection;


/**
 * Class DetectorConditions
 * @package eloquentFilter\QueryFilter\Detection
 */
class DetectorConditions
{
    /**
     * @var
     */
    private $detector;

    /**
     * DetectorConditions constructor.
     * @param array $detector
     */
    public function __construct(array $detector)
    {
        foreach ($detector as $detector_obj) {
            if ($detector_obj instanceof Detector) {
                $this->detector[] = $detector_obj;
            }
        }
    }

    /**
     * @param string $field
     * @param $params
     * @return |null
     */
    public function detect(string $field, $params): ?string
    {
        foreach ($this->detector as $detector_obj) {

            $out = $detector_obj->detect($field, $params);
            if (!empty($out)) {
                return $out;
            }
        }
        return null;
    }
}
