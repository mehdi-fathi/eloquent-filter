<?php

namespace eloquentFilter\QueryFilter\Core\EloquentBuilder;

/**
 *
 */
interface QueryBuilderWrapperInterface
{

    /**
     * @param $builder
     */
    public function __construct($builder);

    /**
     * @param mixed $builder
     */
    public function setBuilder($builder): void;

    /**
     * @return mixed
     */
    public function getBuilder();

    /**
     * @return mixed
     */
    public function getAliasListFilter();

    /**
     * @return mixed
     */
    public function getModel();

}
