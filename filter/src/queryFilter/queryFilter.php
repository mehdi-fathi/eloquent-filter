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

    protected $request,$builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function apply(Builder $builder,$table)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value):

            if($value !== "")
            {
                $runMethod=true;
                if(!empty($value['bet'])){
                    if(empty($value['bet']['from']) && empty($value['bet']['to'])){
                        $runMethod=false;
                    }
                }

                if (Schema::hasColumn($table, $name) && $runMethod) {
                    call_user_func([$this , $name] , array_filter([$value]));
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
