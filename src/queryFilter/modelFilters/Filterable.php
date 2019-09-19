<?php

namespace eloquentFilter\QueryFilter\modelFilters;

use eloquentFilter\QueryFilter\queryFilter;

/**
 * Trait Filterable
 *
 * @package eloquentFilter\QueryFilter\modelFilters
 */
trait Filterable
{

    /**
     * @param \eloquentFilter\QueryFilter\modelFilters\modelFilters $query
     * @param \eloquentFilter\QueryFilter\queryFilter               $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter( $query, QueryFilter $filters) :\Illuminate\Database\Eloquent\Builder
    {
        return $filters->apply($query, $this->getTable());
    }
}
