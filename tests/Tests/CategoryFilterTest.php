<?php

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Illuminate\Http\Request;
use Tests\Controllers\CategoriesController;
use Tests\Models\Category;

class CategoryFilterTest extends TestCase
{
    /** @test */
    public function itCanGetCategoryByCategoryAndDateNull()
    {
        $request = new Request();
        $request->merge(
            [
                'category'   => 'Html',
                'created_at' => null,
            ]
        );
        $modelfilter = new modelFilters(
            $request
        );
        $category = CategoriesController::filter_category($modelfilter);
        $category_pure = Category::where([
            'category'   => 'Html',
            'created_at' => null,
        ])->get();

        $this->assertEquals($category, $category_pure);
    }
}
