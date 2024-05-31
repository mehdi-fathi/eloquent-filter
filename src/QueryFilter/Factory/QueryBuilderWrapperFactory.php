<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapper;
use eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface;
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
    public static function createEloquentQueryBuilder($builder): EloquentModelBuilderWrapper
    {
        return new EloquentModelBuilderWrapper($builder);
    }

    /**
     * @param $builder
     * @return \eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface
     */
    public static function createDbQueryBuilder($builder): DbBuilderWrapperInterface
    {
        return new DbBuilderWrapper($builder);
    }
}
