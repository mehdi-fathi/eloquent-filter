<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\modelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category.
 */
class Category extends Model
{
    use Filterable;
    protected $fillable = [
        'category',
    ];
    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    protected $guarded = [];
}
