<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereByOpt.
 */
class WhereByOpt extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        $opt = $this->values['operator'];
        $value = $this->values['value'];

        return $query->where("$this->filter", "$opt", $value);
    }
}
