<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\QueryFilter\HelperFilter;

/**
 * Class ModelFilters.
 */
class ModelFilters
{
    use HelperFilter;

    protected $builder;
    protected $queryBuilder;

    public function __construct($builder,$queryBuilder)
    {
        $this->builder = $builder;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param $field
     * @param $arguments
     *
     * @throws \Exception
     */
    public function resolveQuery($field, $arguments)
    {
        if ($this->handelListFields($field)) {
            if ($this->checkModelHasOverrideMethod($field)) {
                $this->builder->getModel()->$field($this->builder, $arguments);
            } else {
                $this->queryBuilder->buildQuery($field, $arguments);
            }
        }
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function checkModelHasOverrideMethod(string $field): bool
    {
        if (method_exists($this->builder->getModel(), $field)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $field
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function handelListFields(string $field)
    {
        if ($output = $this->checkSetWhiteListFields($field)) {
            return $output;
        } elseif ($field == 'f_params' || $field == 'or') {
            return true;
        } elseif ($this->checkModelHasOverrideMethod($field)) {
            return true;
        }

        $class_name = class_basename($this->builder->getModel());

        throw new \Exception("You must set $field in whiteListFilter in $class_name.php
         or create a override method with name $field or call ignoreRequest function for ignore $field.");
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function checkSetWhiteListFields(string $field): bool
    {
        if (in_array($field, $this->builder->getModel()->getWhiteListFilter()) ||
            $this->builder->getModel()->getWhiteListFilter()[0] == '*') {
            return true;
        }

        return false;
    }
}
