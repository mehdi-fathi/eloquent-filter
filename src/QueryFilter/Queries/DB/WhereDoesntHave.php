<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Query\Expression as Raw;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $from = $query->from;
        $tableJoin = Str::singular($this->values);
        $foreignKey = sprintf('"%s"."%s_id"', $from, $tableJoin);

        $key = $this->values . '.id';

        return $query->from($from)->whereNotExists(function ($q) use ($foreignKey, $from, $key) {
            $q->select(DB::raw(1))->from($this->values)->where($key, '=', new Raw($foreignKey));
        });
    }
}
