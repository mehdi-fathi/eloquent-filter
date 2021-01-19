<?php

namespace eloquentFilter\Facade;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static string apply(Illuminate\Database\Eloquent\Builder $builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null) :Illuminate\Database\Eloquent\Builder
 * @method static string filterRequests($index = null)
 *
 * @see eloquentFilter\QueryFilter\QueryFilter
 */
class EloquentFilter extends BaseFacade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'eloquentFilter';
    }
}
