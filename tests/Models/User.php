<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\modelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Tests\Models\Filters\usersFilter;

/**
 * Class User.
 */
class User extends Model
{
    use usersFilter,Filterable;

    /**
     * @var array
     */
    public $whiteListFilter = [
        'id',
        'username',
        'email',
        'created_at',
        'updated_at',
    ];
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
