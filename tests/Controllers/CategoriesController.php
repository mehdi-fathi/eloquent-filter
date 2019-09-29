<?php

namespace Tests\Controllers;

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Tests\Models\Category;

/**
 * Class CategoriesController
 *
 * @package Tests\Controllers
 */
class CategoriesController
{
    /**
     * @param \eloquentFilter\QueryFilter\modelFilters\modelFilters $filters
     *
     * @return mixed
     */
    public static function filter_category(modelFilters $filters)
    {
        $categories = Category::filter($filters)->get();

        return $categories;
    }
}
