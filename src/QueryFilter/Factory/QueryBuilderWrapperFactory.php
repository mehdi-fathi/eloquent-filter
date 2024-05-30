<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\EloquentModelBuilderWrapper;

/**
 *
 */
class QueryBuilderWrapperFactory
{
    /**
     * @param $builder
     * @return \eloquentFilter\QueryFilter\Core\EloquentBuilder\EloquentModelBuilderWrapper
     */
    public static function createQueryBuilder($builder): EloquentModelBuilderWrapper
    {
        return new EloquentModelBuilderWrapper($builder);
    }
}
