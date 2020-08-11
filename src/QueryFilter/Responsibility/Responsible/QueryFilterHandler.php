<?php

namespace eloquentFilter\QueryFilter\Responsibility\Responsible;

use eloquentFilter\QueryFilter\Responsibility\FilterHandler;

class QueryFilterHandler extends FilterHandler
{
    protected function processing($field, $arguments): ?string
    {
        return $this->queryBuilder->buildQuery($field, $arguments);
    }
}
