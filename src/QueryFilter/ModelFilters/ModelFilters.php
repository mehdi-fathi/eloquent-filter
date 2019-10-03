<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\QueryFilter\QueryFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class ModelFilters.
 */
class ModelFilters extends QueryFilter
{

    private $_specificFields = [
        'operator'
    ];

    /**
     * @param $field
     * @param $arguments
     *
     * @throws \Exception
     */
    public function __call($field, $arguments)
    {
        if ($this->handelWhiteListFields($field)) {
            if (!$this->checkModelHasOverrideMethod($field)) {
                $this->queryBuilder->buildQuery($field, $arguments);
            } else {
                $this->builder->getModel()->$field($this->builder, $arguments[0]);
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
        if (Schema::hasColumn($this->table, $field) &&
            !method_exists($this->builder->getModel(), $field)) {
            return false;
        }

        return true;
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
            if (in_array($field, $this->builder->getModel()->whiteListFilter) ||
                $this->builder->getModel()->whiteListFilter[0] == '*') {
                return true;
            }
        } else {
            return true;
        }

        $class_name = class_basename($this->builder->getModel());

        throw new \Exception("You must set $field in whiteListFilter in $class_name");
    }

    public function handelSpecificFields(string $field)
    {
        if (in_array($field, $this->_specificFields)) {
            return true;
        }
        return true;
    }
}
