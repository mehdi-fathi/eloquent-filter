<?php

namespace eloquentFilter\QueryFilter\Responsibility\Responsible;

use eloquentFilter\QueryFilter\Responsibility\FilterHandler;

/**
 * Class QueryFilterHandler.
 */
class QueryFilterHandler extends FilterHandler
{
    /**
     * @param $field
     * @param $arguments
     *
     * @return string|null
     */
    protected function processing($field, $arguments): ?string
    {
        return $this->queryBuilder->buildQuery($field, $arguments);
    }
}
