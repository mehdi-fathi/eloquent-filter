<?php

namespace eloquentFilter\QueryFilter\Core;

/**
 *
 */
class QueryBuilderWrapperFactory
{

    public static function createQueryBuilder($builder): QueryBuilderWrapper
    {
        return new QueryBuilderWrapper($builder);
    }

}
