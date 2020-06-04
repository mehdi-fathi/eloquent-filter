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
    use usersFilter;
    use Filterable;
    /**
     * @var array
     */
    private static $whiteListFilter = [
        'id',
        'username',
        'family',
        'email',
        'count_posts',
        'created_at',
        'updated_at',
        'orders.name',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('Tests\Models\Order');
    }
}
