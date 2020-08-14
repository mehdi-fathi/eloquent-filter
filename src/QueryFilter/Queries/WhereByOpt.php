<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

class WhereByOpt extends BaseClause
{
    public function apply($query): Builder
    {
        $opt = $this->values['operator'];
        $value = $this->values['value'];

        return $query->where("$this->filter", "$opt", $value);
    }
}
