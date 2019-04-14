<?php

namespace eloquentFilter\QueryFilter\modelFilters;


use eloquentFilter\QueryFilter\queryFilter;

trait Filterable
{

    public function scopeFilter($query, QueryFilter $filters)
    {

        dd('run this');
        return $filters->apply($query, $this->getTable());
    }
}
