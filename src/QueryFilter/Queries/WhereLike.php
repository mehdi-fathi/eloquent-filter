<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

class WhereLike extends BaseClause
{
    public function apply($query): Builder
    {
        return $query->where("$this->filter", 'like', $this->values['like']);
    }
}
