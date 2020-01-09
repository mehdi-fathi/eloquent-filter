<?php

namespace Tests\Seeds;

use Faker\Factory as FakerFActory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PostTableSeeder.
 */
class PostTableSeeder extends Seeder
{
    /**
     * @var array
     */
    private $data = [];

    public function make_array_data()
    {
    }

    public function run()
    {
//        DB::table('posts')->delete();

        $this->make_array_data();

        $faker = FakerFActory::create();

        $users_id = [
            [
                'user_id' => \Tests\Models\User::where('email', 'mehdifathi.developer@gmail.com')->first()['id'],
            ],
            [
                'user_id' => \Tests\Models\User::where('email', 'ali.2000@gmail.com')->first()['id'],
            ],
            [
                'user_id' => \Tests\Models\User::where('email', 'ahmad.x1998@gmail.com')->first()['id'],
            ],
        ];

        foreach (range(1, 100) as $index) {
            $this->data[0] = [
                'post'       => $faker->text,
                'user_id'    => $users_id[0]['user_id'],
                'created_at' => now()->addDays(rand(1, 30)),
                'updated_at' => now()->addDays(rand(30, 40)),
            ];
            $this->data[1] = [
                'post'       => $faker->text,
                'user_id'    => $users_id[1]['user_id'],
                'created_at' => now()->addDays(rand(1, 30)),
                'updated_at' => now()->addDays(rand(30, 40)),
            ];
            $this->data[2] = [
                'post'       => $faker->text,
                'user_id'    => $users_id[2]['user_id'],
                'created_at' => now()->addDays(rand(1, 30)),
                'updated_at' => now()->addDays(rand(30, 40)),
            ];

            DB::table('posts')->insert(
                $this->data
            );
        }
    }
}
