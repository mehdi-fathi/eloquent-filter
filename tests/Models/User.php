<?php

namespace Tests\Models;

use \Illuminate\Database\Eloquent\Model;

use eloquentFilter\QueryFilter\queryFilter;

class User extends Model
{

    protected $table = 'users';
    protected $guarded = [];

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query, $this->getTable());
    }

    public function posts()
    {
        return $this->hasMany('Tests\Models\Post');
    }

}
