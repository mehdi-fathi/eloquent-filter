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
        if ($this->handelWhiteListFields($field)) {
            if ($this->checkModelHasOverrideMethod($field)) {
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
    private function checkModelHasOverrideMethod(string $field): bool
    {
        if (method_exists($this->builder->getModel(), $field)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $field
     *
     * @return bool
     * @throws \Exception
     *
     */
    private function handelWhiteListFields(string $field)
    {
        if (Schema::hasColumn($this->table, $field)) {
            if (in_array($field, $this->builder->getModel()->getWhiteListFilter()) ||
                $this->builder->getModel()->getWhiteListFilter()[0] == '*') {
                return true;
            }
        } elseif ($field == 'f_params') {
            return true;
        } elseif ($this->checkModelHasOverrideMethod($field)) {
            return true;
        }
        $class_name = class_basename($this->builder->getModel());

        throw new \Exception("You must set $field in whiteListFilter in $class_name");
    }
}
