<?php


namespace eloquentFilter\QueryFilter\Queries;


use Illuminate\Database\Eloquent\Builder;

class WhereBetween extends BaseClause
{

    public function apply($query): Builder
    {

//        dd($this->values,$this->filter);

//        return $query->where($this->filter, $this->values);

        $start = $this->values['start'];
        $end = $this->values['end'];
        return $query->whereBetween($this->filter, [$start, $end]);
    }

}
