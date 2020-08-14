<?php


namespace eloquentFilter\QueryFilter;

use Closure;

abstract class Filter
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);
        return $this->applyFilters($builder);
    }

    protected abstract function applyFilters($builder);
}
