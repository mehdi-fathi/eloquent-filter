<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Faker\Factory as FakerFActory;

class PostTableSeeder extends Seeder
{
    private $data = [];

    public function make_array_data()
    {

        $faker = FakerFActory::create();

        $this->data = [
            [
                'post' => $faker->text,
                'user_id' => \Tests\Models\User::where('email', 'mehdifathi.developer@gmail.com')->first()['id'],
            ],
            [
                'post' => $faker->text,
                'user_id' =>
                    \Tests\Models\User::where('email', 'ali.2000@gmail.com')->first()['id'],
            ],
            [
                'post' => $faker->text,
                'user_id' =>
                    \Tests\Models\User::where('email', 'ahmad.x1998@gmail.com')->first()['id'],
            ]
        ];
    }

    public function run()
    {

        DB::table('posts')->delete();

        $this->make_array_data();

//        foreach (range(1, 100) as $index) :


        DB::table('posts')->insert(
            $this->data
        );
//        endforeach;
    }
}
