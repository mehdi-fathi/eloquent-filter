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
     * @param null $model
     *
     * @throws \Exception
     *
     * @return string|null
     */
    public function detect(string $field, $params, $model = null): ?string
    {
        foreach ($this->detector as $detector_obj) {
            if ($this->handelListFields($field, $model)) {
                $out = $detector_obj::detect($field, $params, $model);
                if (!empty($out)) {
                    return $out;
                }
            }
        }

        return null;
    }

    /**
     * @param string $field
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function handelListFields(string $field, $query)
    {
        if ($output = $this->checkSetWhiteListFields($field, $query)) {
            return $output;
        } elseif ($field == 'f_params' || $field == 'or') {
            return true;
        } elseif ($this->checkModelHasOverrideMethod($field, $query)) {
            return true;
        }

        $class_name = class_basename($query);

        throw new \Exception("You must set $field in whiteListFilter in $class_name.php
         or create a override method with name $field or call ignoreRequest function for ignore $field.");
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    protected function checkModelHasOverrideMethod(string $field, $query): bool
    {
        if (method_exists($query, $field)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function checkSetWhiteListFields(string $field, $query): bool
    {
        if (in_array($field, $query->getWhiteListFilter()) ||
            $query->getWhiteListFilter()[0] == '*') {
            return true;
        }

        return false;
    }
}
