<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;

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
    public function apply($query)
    {
        $opt = $this->values['operator'];
        $value = $this->values['value'];

        return $query->where("$this->filter", "$opt", $value);
    }
}
