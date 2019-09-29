<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\modelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Tests\Models\Filters\usersFilter;

/**
 * Class User
 *
 * @package Tests\Models
 */
class User extends Model
{
    use usersFilter,Filterable;

    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('Tests\Models\Post');
    }
}
