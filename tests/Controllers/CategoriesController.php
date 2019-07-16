<?php

namespace Tests\Controllers;

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Tests\Models\Category;

class CategoriesController
{
    public static function filter_category(modelFilters $filters)
    {
        $categories = Category::filter($filters)->get();

        return $categories;
    }
}
