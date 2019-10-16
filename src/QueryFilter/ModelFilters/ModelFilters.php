<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\QueryFilter\QueryFilter;
use Illuminate\Support\Facades\Schema;

/**
 * Class ModelFilters.
 */
class ModelFilters extends QueryFilter
{
    /**
     * @param $field
     * @param $arguments
     *
     * @throws \Exception
     */
    public function __call($field, $arguments)
    {
        if ($this->__handelListFields($field)) {
            if ($this->__checkModelHasOverrideMethod($field)) {
                $this->builder->getModel()->$field($this->builder, $arguments[0]);
            } else {
                $this->queryBuilder->buildQuery($field, $arguments);
            }
        }
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function __checkModelHasOverrideMethod(string $field): bool
    {
        if (method_exists($this->builder->getModel(), $field)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $field
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function __handelListFields(string $field)
    {

        if ($output = $this->__checkSetWhiteListFields($field)) {
            return $output;
        } elseif ($field == 'f_params') {
            return true;
        } elseif ($this->__checkModelHasOverrideMethod($field)) {
            return true;
        }
        $class_name = class_basename($this->builder->getModel());

        throw new \Exception("You must set $field in whiteListFilter in $class_name");
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function __checkSetWhiteListFields(string $field): bool
    {
        if (Schema::hasColumn($this->table, $field)) {
            if (in_array($field, $this->builder->getModel()->getWhiteListFilter()) ||
                $this->builder->getModel()->getWhiteListFilter()[0] == '*') {
                return true;
            }
        }
        return false;
    }
}
