<?php

namespace eloquentFilter\QueryFilter\modelFilters;

use eloquentFilter\QueryFilter\queryFilter;
use Illuminate\Support\Facades\Schema;

class modelFilters extends queryFilter
{
    public function __call($name, $arguments)
    {
        if (Schema::hasColumn($this->table, $name) &&
            !method_exists($this->builder->getModel(), $name)) {

            if (!empty($arguments[0]['from']) && !empty($arguments[0]['to'])) {
                $arg['from'] = $arguments[0]['from'];
                $arg['to'] = $arguments[0]['to'];

                $this->builder->whereBetween($name, [$arg['from'], $arg['to']]);
            } else {
                $this->builder->where("$name", $arguments[0]);
            }

        } else {
            $this->builder->getModel()->$name($this->builder, $arguments[0]);
        }
    }
}
