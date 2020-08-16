<?php

namespace eloquentFilter\QueryFilter;

use Closure;

/**
 * Class Filter
 * @package eloquentFilter\QueryFilter
 */
abstract class Filter
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $this->applyFilters($builder);
    }

    /**
     * @param $builder
     * @return mixed
     */
    abstract protected function applyFilters($builder);
}
