<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Models\Database;
use Models\Question;

use Controllers\Users;

class UserFilterTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicSeeder()
    {

        $request = new \Illuminate\Http\Request();

        $request->merge(
            [
                'username' => 'mehdi',
                'email' => 'mehdifathi.developer@gmail.com'
            ]
        );

        $modelfilter = new  \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        $users = \Tests\Controllers\Users::filter_user($modelfilter);


        $users_pure = \Tests\Models\User::where([
            'username' => 'mehdi',
            'email' => 'mehdifathi.developer@gmail.com'
        ])->get();

        $this->assertEquals($users_pure, $users);    // Succeeds

        // ...
    }
}
