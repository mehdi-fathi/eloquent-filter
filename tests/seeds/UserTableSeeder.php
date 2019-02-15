<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Faker\Factory as FakerFActory;

class UserTableSeeder extends Seeder
{
    public function run()
    {

        DB::table('users')->delete();

        $faker = FakerFActory::create();

        foreach (range(1,100) as $index) {

            DB::table('users')->insert([
                'username' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('secret'),
            ]);
        }
    }
}
