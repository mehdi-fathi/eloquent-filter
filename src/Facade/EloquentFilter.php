<?php

namespace eloquentFilter\Facade;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static object apply($builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detections_injected = null,array $black_list_detections = null)
 * @method static array|string filterRequests($index = null)
 * @method static array getAcceptedRequest()
 * @method static array getIgnoredRequest()
 * @method static array getInjectedDetections()
 * @method static array getResponse()
 * @method static array setRequestEncoded(?array $request, $salt)
 * @method static array getRequestEncoded()
 *
 * @see eloquentFilter\QueryFilter\QueryFilter
 */
class EloquentFilter extends BaseFacade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'eloquentFilter';
    }
}
