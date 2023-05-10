<?php

namespace eloquentFilter\QueryFilter\Core\EloquentBuilder;

/**
 *
 */
class QueryBuilderWrapper implements QueryBuilderWrapperInterface
{

    /**
     * @param mixed $builder
     */
    public function __construct(private mixed $builder)
    {
    }

    /**
     * @return mixed
     */
    public function getBuilder(): mixed
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
    public function getAliasListFilter(): mixed
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
