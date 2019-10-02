<?php
/**
 * Copyright (c) 2019. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

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
            if (!$this->checkModelHasOverrideMethod($field)) {

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

    /**
     * @param string $field
     *
     * @return bool
     */
    private function checkModelHasOverrideMethod(string $field):bool
    {
        if (Schema::hasColumn($this->table, $field) &&
            !method_exists($this->builder->getModel(), $field)){
            return false;
        }
        return true;
    }

    /**
     * @param string $field
     *
     * @return bool
     * @throws \Exception
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
}
