<?php


namespace eloquentFilter\QueryFilter\Responsibility\Responsible;


use DesignPatterns\Behavioral\ChainOfResponsibilities\Handler;
use eloquentFilter\QueryFilter\Queries\QueryBuilder;
use eloquentFilter\QueryFilter\Responsibility\FilterHandler;

class CustomQueryFilterHandler extends FilterHandler
{

    public function __construct(FilterHandler $handler = null)
    {
        parent::__construct($handler);

    }

    protected function processing($field, $arguments)
    {
        // this is a mockup, in production code you would ask a slow (compared to in-memory) DB for the results

        if ($this->checkModelHasOverrideMethod($field)) {
            return $this->queryBuilder->getBuilder()->getModel()->$field($this->queryBuilder->getBuilder(), $arguments);
        }
        return null;
    }

}
