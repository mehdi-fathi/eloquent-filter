<?php

namespace eloquentFilter\QueryFilter\Core\EloquentBuilder;

/**
 *
 */
class EloquentModelBuilderWrapper implements QueryBuilderWrapperInterface
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
    public function getModel(): mixed
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
    public function serializeRequestFilter($request): mixed
    {
        return $this->getBuilder()->getModel()->serializeRequestFilter($request);

    }

    /**
     * @param $response
     * @return mixed
     */
    public function getResponseFilter($response): mixed
    {
        return $this->getBuilder()->getModel()->getResponseFilter($response);

    }
}
