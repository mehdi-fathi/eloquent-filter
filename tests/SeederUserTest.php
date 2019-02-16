<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Models\Database;
use Models\Question;
class SeederUserTest extends TestCase
{


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $seeder = new UserTableSeeder();
        $seeder->run();
        $response = $this->get('/');

        // ...
    }
}
