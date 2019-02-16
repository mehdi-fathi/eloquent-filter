<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Models\Database;
use Models\Question;

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

            DB::unprepared(file_get_contents('tests/seeds/eloquentFilter_test.sql'));

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
