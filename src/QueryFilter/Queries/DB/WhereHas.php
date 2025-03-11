<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Query\Expression as Raw;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class WhereHas.
 * Implements WhereHas functionality for DB queries using EXISTS clause
 */
class WhereHas extends BaseClause
{
    /**
     * Apply the WhereHas condition to the DB query
     * 
     * @param $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function apply($query)
    {
        $field_row = explode('.', $this->filter);
        $field_row = end($field_row);
        
        $relation = str_replace('.'.$field_row, '', $this->filter);
        $relationTable = Str::plural($relation);
        
        $value = $this->values;
        $from = $query->from;

        return $query->whereExists(function ($q) use ($relationTable, $field_row, $value, $from) {
            $foreignKey = sprintf('%s.%s_id', $from, Str::singular($relationTable));
            
            $q->select(DB::raw(1))
              ->from($relationTable)
              ->whereRaw("$relationTable.id = $foreignKey");

            if (is_array($value)) {
                $q->whereIn($field_row, $value);
            } else {
                $q->where($field_row, $value);
            }
        });
    }
}
