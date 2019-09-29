<?php

namespace Tests\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Tests\Models\Category;
use Tests\Models\Post;

/**
 * Class CategoriesPostsTableSeeder.
 */
class CategoriesPostsTableSeeder extends Seeder
{
    public function run()
    {
//        DB::table('categories_posts')->delete();

        foreach (Post::all() as $index => $post) {
            foreach (range(rand(1, 4), 4) as $index_range) {
                $category_post_id[$index]['post_id'] = $post['id'];
                $category_post_id[$index]['created_at'] = $post['created_at'];
                $category_post_id[$index]['updated_at'] = $post['updated_at'];
                $category_post_id[$index]['category_id'][] = Category::inRandomOrder()->first()['id'];
            }

            $category_post_id[$index]['category_id'] = array_unique($category_post_id[$index]['category_id']);
        }

        foreach ($category_post_id as $category_post_id_data) {
            $post_id = $category_post_id_data['post_id'];
            $created_at = $category_post_id_data['created_at'];
            $updated_at = $category_post_id_data['updated_at'];

            foreach ($category_post_id_data['category_id'] as $category_id) {
                $data = [
                    'post_id'     => $post_id,
                    'category_id' => $category_id,
                    'created_at'  => $created_at,
                    'updated_at'  => $updated_at,
                ];

                DB::table('categories_posts')->insert($data);
            }
        }
    }
}
