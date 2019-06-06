<?php

namespace Tests\Models;

use \Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\modelFilters\Filterable;
use Tests\Models\Filters\usersFilter;

class User extends Model
{

    use usersFilter,Filterable;

    protected $table = 'users';
    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany('Tests\Models\Post');
    }

}
