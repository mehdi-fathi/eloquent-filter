<?php

namespace eloquentFilter\QueryFilter\modelFilters;

use eloquentFilter\QueryFilter\queryFilter;

trait Filterable
{
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query, $this->getTable());
    }
}
