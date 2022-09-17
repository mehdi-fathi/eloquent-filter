<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\EloquentQueryFilterCore;
use eloquentFilter\QueryFilter\Core\QueryFilterCore;

/**
 * Class QueryFilterCore.
 */
class QueryFilterCoreFactory
{
    public function createQueryFilterCoreBuilder($request) : QueryFilterCore
    {
        return new EloquentQueryFilterCore($request);
    }
}
