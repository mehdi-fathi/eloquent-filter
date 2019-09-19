<?php

namespace eloquentFilter\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class queryFilter.
 */
class queryFilter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var
     */
    protected $builder;
    /**
     * @var
     */
    protected $table;

    /**
     * queryFilter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $table) :Builder
    {
        $this->builder = $builder;
        $this->table = $table;

        foreach ($this->filters() as $name => $value):
                call_user_func([$this, $name], $value);
        // It resolve methods in filters class in child
        endforeach;

        return $this->builder;
    }

    /**
     * @return array
     */
    public function filters() :array
    {
        return $this->request->all();
    }
}
