<?php

namespace eloquentFilter\QueryFilter\Responsibility;

use eloquentFilter\QueryFilter\Queries\QueryBuilder;

abstract class FilterHandler
{
    private $successor = null;
    protected $queryBuilder = null;

    public function __construct(FilterHandler $handler = null)
    {
        $this->successor = $handler;
    }

    /**
     * This approach by using a template method pattern ensures you that
     * each subclass will not forget to call the successor.
     */
    final public function handle(QueryBuilder $queryBuilder, $field, $arguments)
    {
        $this->queryBuilder = $queryBuilder;
        if ($this->handelListFields($field)) {
            $processed = $this->processing($field, $arguments);

            if ($processed === null && $this->successor !== null) {
                // the request has not been processed by this handler => see the next
                $processed = $this->successor->handle($this->queryBuilder, $field, $arguments);
            }

            return $processed;
        }
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
    protected function checkModelHasOverrideMethod(string $field): bool
    {
        if (method_exists($this->queryBuilder->getBuilder()->getModel(), $field)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    private function checkSetWhiteListFields(string $field): bool
    {
//        dd($this->queryBuilder->getBuilder());
        if (in_array($field, $this->queryBuilder->getBuilder()->getModel()->getWhiteListFilter()) ||
            $this->queryBuilder->getBuilder()->getModel()->getWhiteListFilter()[0] == '*') {
            return true;
        }

        return false;
    }

    abstract protected function processing($field, $arguments);
}
