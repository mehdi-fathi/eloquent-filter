<?php

namespace eloquentFilter\QueryFilter\Core;

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

    public function getAliasListFilter();

    public function getModel();

}
