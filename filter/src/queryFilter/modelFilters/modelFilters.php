<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 7/25/18
 * Time: 9:14 PM
 */

namespace eloquentFilter\QueryFilter\modelFilters;

use Zaman;
use Config;
use eloquentFilter\QueryFilter\queryFilter;

class modelFilters extends queryFilter
{



    public function __call($name, $arguments)
    {
        if (!empty($arguments[0][0]['bet'])) {

            $arg['from'] = $arguments[0][0]['bet']['from'];
            $arg['to'] = $arguments[0][0]['bet']['to'];

            $this->builder->whereBetween($name, [$arg['from'], $arg['to']]);
        } else {
            $this->builder->where("$name", $arguments);
        }

    }

}
