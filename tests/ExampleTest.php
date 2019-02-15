<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Models\Database;
use Models\Question;
class ExampleTest extends TestCase
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
