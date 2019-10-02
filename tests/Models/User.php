<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
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
        'family',
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
