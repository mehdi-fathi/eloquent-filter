<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper;

/**
 *
 */
class QueryBuilderWrapperFactory
{
    /**
     * @param $builder
     * @return \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper
     */
    public static function createQueryBuilder($builder): QueryBuilderWrapper
    {
        return new QueryBuilderWrapper($builder);
    }
}
