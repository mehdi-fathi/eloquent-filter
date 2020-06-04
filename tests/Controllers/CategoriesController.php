<?php

namespace Tests\Controllers;

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Tests\Models\Category;

/**
 * Class CategoriesController.
 */
class CategoriesController
{
    /**
     * @param \eloquentFilter\QueryFilter\ModelFilters\ModelFilters $filters
     *
     * @return mixed
     */
    public static function filterCategory($filters)
    {
        $categories = Category::filter($filters)->get();

        return $categories;
    }
}
