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
                'email'      => 'mehdifathi.developer@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'ali',
                'email'      => 'ali.2000@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'ahmad',
                'email'      => 'ahmad.x1998@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => now()->addDay(10),
                'updated_at' => now(),
            ],
            [
                'username'   => 'reza',
                'email'      => 'reza.rrz@yahoo.com',
                'password'   => bcrypt('secret'),
                'created_at' => '2019-01-06 17:11:46',
                'updated_at' => '2019-01-16 17:11:46',
            ],
            [
                'username'   => 'amir',
                'email'      => 'amirccx1098xx@gmail.com',
                'password'   => bcrypt('secret'),
                'created_at' => '2019-01-06 17:11:46',
                'updated_at' => '2019-01-10 17:11:46',
            ],
            [
                'username'   => 'ahmad',
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
