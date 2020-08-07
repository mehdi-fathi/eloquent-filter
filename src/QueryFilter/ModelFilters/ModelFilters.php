<?php

namespace eloquentFilter\QueryFilter\ModelFilters;

use eloquentFilter\QueryFilter\HelperFilter;
use eloquentFilter\QueryFilter\Queries\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ModelFilters.
 */
class ModelFilters
{
    use HelperFilter;

    /**
     * @var
     */
    protected $queryBuilder;

    /**
     * ModelFilters constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
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
                $this->queryBuilder->getBuilder()->getModel()->$field($this->queryBuilder->getBuilder(), $arguments);
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
        if (method_exists($this->queryBuilder->getBuilder()->getModel(), $field)) {
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

        $class_name = class_basename($this->queryBuilder->getBuilder()->getModel());

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
        if (in_array($field, $this->queryBuilder->getBuilder()->getModel()->getWhiteListFilter()) ||
            $this->queryBuilder->getBuilder()->getModel()->getWhiteListFilter()[0] == '*') {
            return true;
        }

        return false;
    }
}
