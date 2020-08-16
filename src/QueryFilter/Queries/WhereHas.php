<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

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
    public function apply($query): Builder
    {
        $field_row = explode('.', $this->filter);
        $field_row = end($field_row);

        $conditions = str_replace('.'.$field_row, '', $this->filter);

        $value = $this->values;

        return $query->whereHas(
            $conditions,
            function ($q) use ($value, $field_row) {
                $condition = 'where';
                if (is_array($value)) {
                    $condition = 'whereIn';
                }
                $q->$condition($field_row, $value);
            }
        );
    }
}
