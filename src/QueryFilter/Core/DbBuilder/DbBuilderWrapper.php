<?php

namespace eloquentFilter\QueryFilter\Core\DbBuilder;


/**
 *
 */
class DbBuilderWrapper implements DbBuilderWrapperInterface
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

}
