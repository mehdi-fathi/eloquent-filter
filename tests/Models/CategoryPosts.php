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
        'username',
        'name',
        'count_posts',
    ];

    public function ResponseFilter($out)
    {
        $data['data'] = $out;

        return $data;
    }
}
