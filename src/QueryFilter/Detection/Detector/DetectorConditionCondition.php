<?php

namespace eloquentFilter\QueryFilter\Detection\Detector;

use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;
use eloquentFilter\QueryFilter\Detection\Contract\DetectorConditionContract;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use eloquentFilter\QueryFilter\Queries\Eloquent\WhereCustom;
use Exception;

/**
 * Class DetectorConditions.
 */
class DetectorConditionCondition implements DetectorConditionContract
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
                if ($reflect->implementsInterface(DefaultConditionsContract::class)) {
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
     * @param null $getWhiteListFilter
     * @param bool $HasOverrideMethod
     * @param $className
     * @return string|null
     */
    public function detect(string $field, $params, $getWhiteListFilter = null, bool $HasOverrideMethod = false, $className = null): ?string
    {
        $out = $this->getDetector()->map(function ($item) use ($field, $params, $getWhiteListFilter, $HasOverrideMethod, $className) {
            if ($this->handelListFields($field, $getWhiteListFilter, $HasOverrideMethod, $className)) {
                if ($HasOverrideMethod) {
                    $query = WhereCustom::class;
                } else {
                    /** @see DefaultConditionsContract::detect() */
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
        if ($this->checkSetWhiteListFields($field, $list_white_filter_model) || ($field == SpecialCondition::SPECIAL_PARAM_NAME || $field == 'or') || $has_method) {
            return true;
        }

        throw new EloquentFilterException(sprintf($this->errorExceptionWhileList, $field, $model_class, $field, $field), 1);
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
