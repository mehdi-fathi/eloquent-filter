<?php

namespace eloquentFilter\QueryFilter\Core;

/**
 *
 */
class QueryBuilderWrapper implements QueryBuilderWrapperInterface
{

    /**
     * @param $builder
     */
    public function __construct($builder)
    {
        $this->setBuilder($builder);
    }

    /**
     * @param mixed $builder
     */
    public function setBuilder($builder): void
    {
        $this->builder = $builder;
    }

    /**
     * @return mixed
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    public function getModel()
    {
        return $this->getBuilder()->getModel();
    }

    public function getAliasListFilter()
    {
        return $this->getModel()->getAliasListFilter();
    }

    public function serializeRequestFilter($request)
    {
       return $this->getBuilder()->getModel()->serializeRequestFilter($request);
    }

}
