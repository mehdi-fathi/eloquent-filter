<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Faker\Factory as FakerFActory;
use Tests\Models\User;

class UserTableSeeder extends Seeder
{
    private  $data = [];
    public function make_array_data()
    {
        $this->data = [
            [
                'username' => 'mehdi',
                'email' => 'mehdifathi.developer@gmail.com',
                'password' => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' =>  now(),
            ],
            [
                'username' => 'ali',
                'email' => 'ali.2000@gmail.com',
                'password' => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' =>  now(),
            ],
            [
                'username' => 'ahmad',
                'email' => 'ahmad.x1998@gmail.com',
                'password' => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' =>  now(),
            ]
        ];
    }

    public function run()
    {

        DB::table('users')->delete();

        $this->make_array_data();

//        $faker = FakerFActory::create();
        DB::table('users')->insert($this->data);
    }
}
