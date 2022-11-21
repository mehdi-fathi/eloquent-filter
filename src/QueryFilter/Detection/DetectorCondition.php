<?php

namespace eloquentFilter\QueryFilter\Detection;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use Exception;

/**
 * Class DetectorConditions.
 */
class DetectorCondition
{
    protected $errorExceptionWhileList = "You must set %s in whiteListFilter in %s
         or create a override method with name %s or call ignoreRequest function for ignore %s.";
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
                if ($reflect->implementsInterface(DetectorConditionsContract::class)) {
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
     * @return string|null
     * @throws Exception
     *
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
     * @param string $field
     * @param array|null $list_white_filter_model
     * @param bool $has_method
     * @param $model_class
     *
     * @return bool
     * @throws Exception
     *
     */
    private function handelListFields(string $field, ?array $list_white_filter_model, bool $has_method, $model_class): bool
    {
        if ($this->checkSetWhiteListFields($field, $list_white_filter_model) || ($field == 'f_params' || $field == 'or') || $has_method) {
            return true;
        }

        $class_name = class_basename($model_class);

        throw new EloquentFilterException(sprintf($this->errorExceptionWhileList, $field, $class_name, $field, $field), 1);
    }

    /**
     * @param string $field
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
