<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class WhereHas.
 */
class WhereHas extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        //todo implement later
        return ;

        // $field_row = explode('.', $this->filter);
        // $field_row = end($field_row);
        //
        // $conditions = str_replace('.'.$field_row, '', $this->filter);
        //
        // $value = $this->values;
        //
        // return DB::table($query->getModel()->getTable())->whereHas(
        //     $conditions,
        //     function ($q) use ($value, $field_row) {
        //         $condition = 'where';
        //         if (is_array($value)) {
        //             $condition = 'whereIn';
        //         }
        //         $q->$condition($field_row, $value);
        //     }
        // );
    }
}
