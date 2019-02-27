<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 7/25/18
 * Time: 9:17 PM
 */

namespace eloquentFilter\QueryFilter;


use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
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


            if ($value !== "") {
                if ($value) {
                    if (!empty($value) || $value === '0') {
                        call_user_func([$this, $name], $value);
                    }
                }
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
