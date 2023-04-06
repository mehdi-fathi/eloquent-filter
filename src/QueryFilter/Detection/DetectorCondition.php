<?php

namespace eloquentFilter\QueryFilter\Detection;

use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use eloquentFilter\QueryFilter\Queries\WhereCustom;
use Exception;

/**
 * Class DetectorConditions.
 */
class DetectorCondition
{
    protected string $errorExceptionWhileList = "You must set %s in whiteListFilter in %s
         or create an override method with name %s or call ignoreRequest method for ignore %s.";
    /**
     * @var \Illuminate\Support\Collection
     */
    private \Illuminate\Support\Collection $detector;

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

        $this->setDetector($detector_collect);
    }

    /**
     * @param \Illuminate\Support\Collection $detector
     */
    public function setDetector(\Illuminate\Support\Collection $detector): void
    {
        $this->detector = $detector;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDetector(): \Illuminate\Support\Collection
    {
        return $this->detector;
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
        $out = $this->getDetector()->map(function ($item) use ($field, $params, $model) {
            if ($this->handelListFields($field, $model->getWhiteListFilter(), $model->checkModelHasOverrideMethod($field), $model)) {
                if ($model->checkModelHasOverrideMethod($field)) {
                    $query = WhereCustom::class;
                } else {
                    $query = $item::detect($field, $params);
                }

                if (!empty($query)) {
                    return $query;
                }
            }
        })->filter();

        return $out->first();
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
        if (in_array($field, $query) || (!empty($query[0]) && $query[0] == '*')) {
            return true;
        }

        return false;
    }
}
