<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
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

        ];
    }

    public function run()
    {
        DB::table('categories')->delete();

        $this->make_array_data();

//        $faker = FakerFActory::create();
        DB::table('categories')->insert($this->data);
    }
}
