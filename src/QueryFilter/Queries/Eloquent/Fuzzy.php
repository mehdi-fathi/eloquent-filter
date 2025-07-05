<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use eloquentFilter\QueryFilter\Queries\QueryHelper\Fuzziable;

/**
 * Class Fuzzy.
 */
class Fuzzy extends BaseClause
{
    use Fuzziable;

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        $pattern = $this->createFuzzyPattern($this->values['fuzzy']);

        return $query->whereRaw("LOWER($this->filter) LIKE LOWER(?) ", $pattern);
    }

} 