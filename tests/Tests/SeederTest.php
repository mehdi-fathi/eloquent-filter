<?php

use Models\Database;
use Models\Question;

use Controllers\Users;

class SeederTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicSeeder()
    {

        if (!Schema::hasTable('users')) {
            DB::unprepared(file_get_contents('tests/Seeds/data-sql/eloquentFilter_test.sql'));
        }

        $seeder = new UserTableSeeder();
        $seeder->run();

        $seeder = new PostTableSeeder();
        $seeder->run();

        $seeder = new CategoryTableSeeder();
        $seeder->run();

        $seeder = new CategoriesPostsTableSeeder();
        $seeder->run();

        $this->assertTrue(true);


        // ...
    }
}
