<?php

namespace eloquentFilter\QueryFilter\Detection;

/**
 * Class DetectorConditions.
 */
class DetectorConditions
{
    /**
     * @var
     */
    private $detector;

    /**
     * DetectorConditions constructor.
     *
     * @param array $detector
     *
     * @throws \ReflectionException
     */
    public function __construct(array $detector)
    {
        foreach ($detector as $detector_obj) {
            $reflect = new \ReflectionClass($detector_obj);
            if ($reflect->implementsInterface(DetectorContract::class)) {
                $this->detector[] = $detector_obj;
            }
        }
    }

    /**
     * @param string $field
     * @param $params
     *
     * @return string|null
     */
    public function detect(string $field, $params): ?string
    {
        foreach ($this->detector as $detector_obj) {
            $out = $detector_obj::detect($field, $params);
            if (!empty($out)) {
                return $out;
            }
        }

        return null;
    }
}
