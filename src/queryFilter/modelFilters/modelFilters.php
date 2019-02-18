<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 7/25/18
 * Time: 9:14 PM
 */

namespace eloquentFilter\QueryFilter\modelFilters;

use Illuminate\Support\Facades\DB;
use Zaman;
use Config;
use eloquentFilter\QueryFilter\queryFilter;

class modelFilters extends queryFilter
{

    public function __call($name, $arguments)
    {

        if (!empty($arguments[0]['from']) && !empty($arguments[0]['to'])) {

            $arg['from'] = $arguments[0]['from'];
            $arg['to'] = $arguments[0]['to'];

            $this->builder->whereBetween($name, [$arg['from'], $arg['to']]);
        } else {
            if(!empty($arguments[0]) && !is_array($arguments[0])){

                $this->builder->where("$name", $arguments[0]);

            }
        }


    }

}
