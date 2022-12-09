<?php

namespace eloquentFilter\QueryFilter\Core\EloquentBuilder;

/**
 *
 */
class QueryBuilderWrapper implements QueryBuilderWrapperInterface
{
    /**
     * @var
     */
    private $builder;

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

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->getBuilder()->getModel();
    }

    /**
     * @return mixed
     */
    public function getAliasListFilter()
    {
        return $this->getModel()->getAliasListFilter();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function serializeRequestFilter($request)
    {
        return $this->getBuilder()->getModel()->serializeRequestFilter($request);
    }

    /**
     * @param $out
     * @return mixed
     */
    public function responseFilter($out)
    {
        return $this->getBuilder()->getModel()->ResponseFilter($out);
    }
}
