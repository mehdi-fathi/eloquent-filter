<?php

namespace eloquentFilter\QueryFilter\Detection;

use Exception;

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
     */
    public function __construct(array $detector)
    {
        $detector_collect = collect($detector);

        $detector_collect->map(function ($detector_obj) {
            if (!empty($detector_obj)) {
                $reflect = new \ReflectionClass($detector_obj);
                if ($reflect->implementsInterface(DetectorContract::class)) {
                    return $detector_obj;
                }
            }
        })->toArray();

        $this->detector = $detector_collect;
    }

    /**
     * @param string $field
     * @param $params
     * @param null $model
     *
     * @throws Exception
     *
     * @return string|null
     */
    public function detect(string $field, $params, $model = null): ?string
    {
        foreach ($this->detector as $detector_obj) {
            if ($this->handelListFields($field, $model->getWhiteListFilter(), $model->checkModelHasOverrideMethod($field), $model)) {
                $out = $detector_obj::detect($field, $params, $model->checkModelHasOverrideMethod($field));
                if (!empty($out)) {
                    return $out;
                }
            }
        }

        return null;
    }

    /**
     * @param string     $field
     * @param array|null $list_white_filter_model
     * @param bool       $has_method
     * @param $model_class
     *
     * @throws Exception
     *
     * @return bool
     */
    private function handelListFields(string $field, ?array $list_white_filter_model, bool $has_method, $model_class)
    {
        if ($output = $this->checkSetWhiteListFields($field, $list_white_filter_model)) {
            return $output;
        } elseif (($field == 'f_params' || $field == 'or') || $has_method) {
            return true;
        }

        $class_name = class_basename($model_class);

        throw new Exception("You must set $field in whiteListFilter in $class_name.php
         or create a override method with name $field or call ignoreRequest function for ignore $field.");
    }

    /**
     * @param string     $field
     * @param array|null $query
     *
     * @return bool
     */
    private function checkSetWhiteListFields(string $field, ?array $query): bool
    {
        if (in_array($field, $query) ||
            $query[0] == '*') {
            return true;
        }

        return false;
    }
}
