<?php

namespace eloquentFilter\QueryFilter;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Model\Filter\Models_Filter\CostsFilters;

class queryFilter
{

    protected $request, $builder, $table;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder, $table)
    {
        $this->builder = $builder;
        $this->table = $table;

        foreach ($this->filters() as $name => $value):
            if ($value !== "" && !empty($value) || $value === '0') {
                call_user_func([$this, $name], $value);
                // It resolve methods in filters class in child
            }
        endforeach;

        return $this->builder;
    }

    public function filters()
    {
        return $this->request->all();
    }
}
