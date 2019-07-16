<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\modelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Filterable;

    protected $table = 'categories';

    protected $guarded = [];
}
