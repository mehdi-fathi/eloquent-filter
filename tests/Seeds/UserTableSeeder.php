<?php

namespace Tests\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    /**
     * @var array
     */
    private $data = [];

    public function make_array_data()
    {
        $this->data = [
            [
                'username'   => 'mehdi',
                'name'       => 'mahdi',
                'family'     => 'fathi',
                'email'      => 'mehdifathi.developer@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'ali',
                'name'       => 'ali',
                'family'     => 'ahmadi',
                'email'      => 'ali.2000@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'ali22',
                'name'       => 'ali',
                'family'     => 'ahmadi',
                'email'      => 'ali.2010.developer@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'ahmad',
                'name'       => 'ahmad',
                'family'     => 'alavi',
                'email'      => 'ahmad.x1998@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now()->addDay(10),
                'updated_at' => now(),
            ],
            [
                'username'   => 'reza',
                'name'       => 'reza',
                'family'     => 'mohamadi',
                'email'      => 'reza.rrz@yahoo.com',
                'password'   => bcrypt('secret'),
                'created_at' => '2019-01-06 17:11:46',
                'updated_at' => '2019-01-16 17:11:46',
            ],
            [
                'username'   => 'amir',
                'name'       => 'amir',
                'family'     => 'mahmoudi',
                'email'      => 'amirccx1098xx@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => '2019-01-06 17:11:46',
                'updated_at' => '2019-01-10 17:11:46',
            ],
            [
                'username'   => 'ahmad',
                'name'       => 'ahmad',
                'family'     => 'kermani',
                'email'      => 'ahmad.x1998@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => '2019-02-06 10:11:46',
                'updated_at' => '2019-03-16 17:11:46',
            ],

        ];
    }

    public function run()
    {
//        DB::table('users')->delete();

        $this->make_array_data();

        DB::table('users')->insert($this->data);
    }
}
