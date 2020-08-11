<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use DesignPatterns\Behavioral\ChainOfResponsibilities\Responsible\HttpInMemoryCacheHandler;
use DesignPatterns\Behavioral\ChainOfResponsibilities\Responsible\SlowDatabaseHandler;
use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Queries\QueryBuilder;
use eloquentFilter\QueryFilter\Responsibility\Responsible\CustomQueryFilterHandler;
use eloquentFilter\QueryFilter\Responsibility\Responsible\QueryFilterHandler;

/**
 * Class ModelFilters.
 */
class ModelFilters
{
    use HelperFilter;

    /**
     * @var
     */
    protected $queryBuilder;

    /**
     * @var CustomQueryFilterHandler
     */
    protected $chainFilterHandler;

    /**
     * ModelFilters constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        $this->chainFilterHandler = new CustomQueryFilterHandler(
            new QueryFilterHandler()
        );
    }

    /**
     * @param $field
     * @param $arguments
     *
     * @throws \Exception
     */
    public function resolveQuery($field, $arguments)
    {
       $this->chainFilterHandler->handle($this->queryBuilder,$field, $arguments);
    }
}
