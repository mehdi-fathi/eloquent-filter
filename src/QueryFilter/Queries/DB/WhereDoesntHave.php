<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Query\Expression as Raw;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class WhereDoesntHave extends BaseClause
{
    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        $foreignKey = $this->values . '.id';

        $from = $query->from;

        $key_from = sprintf('"%s"."category_id"',$from);

        return $query->from($from)->whereNotExists(function ($q) use ($foreignKey, $from,$key_from) {
            $q->select(DB::raw(1))->from($this->values)->where($foreignKey, '=', new Raw($key_from));
        });
    }
}
