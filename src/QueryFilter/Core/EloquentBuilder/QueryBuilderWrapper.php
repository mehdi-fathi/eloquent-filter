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
        if (method_exists($this->getBuilder(), 'getModel')) {
            return $this->getBuilder()->getModel();
        }

        return null;

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

        if (method_exists($this->getBuilder()->getModel(), 'serializeRequestFilter')) {
            return $this->getBuilder()->getModel()->serializeRequestFilter($request);
        }
        return null;
    }

    /**
     * @param $out
     * @return mixed
     */
    public function getResponseFilter($out)
    {
        if (method_exists($this->getBuilder()->getModel(), 'getResponseFilter')) {
            return $this->getBuilder()->getModel()->getResponseFilter($out);
        }
    }
}
