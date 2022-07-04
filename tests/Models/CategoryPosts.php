<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class CategoryPosts extends Model
{
    use Filterable;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'category_id',
        'post_id',
    ];
}
