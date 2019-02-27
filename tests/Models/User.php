<?php

namespace Tests\Models;

use \Illuminate\Database\Eloquent\Model;

use eloquentFilter\QueryFilter\queryFilter;
use Tests\Models\Filters\usersFilter;

class User extends Model
{

    use usersFilter;

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
