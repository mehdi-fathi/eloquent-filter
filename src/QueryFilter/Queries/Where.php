<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

class Where extends BaseClause
{
    public function apply($query): Builder
    {

//        dd($this->values,$this->filter);

        return $query->where($this->filter, $this->values);
    }
}
