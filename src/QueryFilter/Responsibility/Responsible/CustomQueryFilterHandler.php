<?php

namespace eloquentFilter\QueryFilter\Responsibility\Responsible;

use eloquentFilter\QueryFilter\Responsibility\FilterHandler;

/**
 * Class CustomQueryFilterHandler
 * @package eloquentFilter\QueryFilter\Responsibility\Responsible
 */
class CustomQueryFilterHandler extends FilterHandler
{
    /**
     * CustomQueryFilterHandler constructor.
     * @param FilterHandler|null $handler
     */
    public function __construct(FilterHandler $handler = null)
    {
        parent::__construct($handler);
    }

    /**
     * @param $field
     * @param $arguments
     * @return mixed|null
     */
    protected function processing($field, $arguments)
    {
        if ($this->checkModelHasOverrideMethod($field)) {
            return $this->queryBuilder->getBuilder()->getModel()->$field($this->queryBuilder->getBuilder(), $arguments);
        }
        return null;
    }
}
