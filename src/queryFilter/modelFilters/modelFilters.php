<?php

namespace eloquentFilter\QueryFilter\modelFilters;

use eloquentFilter\QueryFilter\queryFilter;
use Illuminate\Support\Facades\Schema;

/**
 * Class modelFilters.
 */
class modelFilters extends queryFilter
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

            if (Schema::hasColumn($this->table, $field) &&
                !method_exists($this->builder->getModel(), $field)) {

                if (!empty($arguments[0]['from']) && !empty($arguments[0]['to'])) {
                    $arg['from'] = $arguments[0]['from'];
                    $arg['to'] = $arguments[0]['to'];
                    $this->builder->whereBetween($field, [$arg['from'], $arg['to']]);
                } else {
                    $this->builder->where("$field", $arguments[0]);
                }
            } else {
                $this->builder->getModel()->$field($this->builder, $arguments[0]);
            }

        }

    }

    private function handelWhiteListFields($field)
    {
        if (Schema::hasColumn($this->table, $field)) {
            if (in_array($field, $this->builder->getModel()->whiteListFilter) ||
                $this->builder->getModel()->whiteListFilter[0] == '*') {
                return true;
            }
        }else{
            return true;
        }

        $class_name = class_basename($this->builder->getModel());
        throw new \Exception("You must set $field in whiteListFilter in $class_name");
    }
}
