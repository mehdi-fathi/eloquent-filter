<?php

namespace eloquentFilter\Facade;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static object apply(Illuminate\Database\Eloquent\Builder $builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null)
 * @method static array|string filterRequests($index = null)
 * @method static array getAcceptedRequest()
 * @method static array getIgnoredRequest()
 * @method static array getInjectedDetections()
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
