<?php

namespace Tests\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Tests\Models\Category;

/**
 * Class CategoryTableSeeder.
 */
class CategoryTableSeeder extends Seeder
{
    /**
     * @var array
     */
    private $data = [];

    public function make_array_data()
    {
        $this->data = [
            [
                'category'   => 'PHP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category'   => 'JS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category'   => 'ASP.net',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category'   => 'jquery',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category'   => 'Html',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'category'   => 'Html',
                'created_at' => now(),
                'updated_at' => null,
            ],

        ];
    }

    public function run()
    {
//        DB::table('categories')->delete();

        $this->make_array_data();

//        $faker = FakerFActory::create();
        DB::table('categories')->insert($this->data);

//        factory(Category::class)->create($this->data);
    }
}
