<?php

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Illuminate\Http\Request;
use Tests\Controllers\UsersController;
use Tests\Models\User;
use Tests\Seeds\UserTableSeeder;

/**
 * Class UserFilterTest.
 */
class UserFilterTest extends TestCase
{
    private function __intiDb()
    {
        $seeder = new UserTableSeeder();
        $seeder->run();
    }

    /** @test */
    public function itCanGetUserByEmailAndUsername()
    {
        $this->__intiDb();

        $request = new Request();

        $request->merge(
            [
                'username' => 'mehdi',
                'email'    => 'mehdifathi.developer@gmail.com',
            ]
        );

        $modelfilter = new modelFilters(
            $request
        );

        $users = UsersController::filter_user($modelfilter);

        $users_pure = User::where([
            'username' => 'mehdi',
            'email'    => 'mehdifathi.developer@gmail.com',
        ])->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByCustomfilter()
    {
        $this->__intiDb();

        $request = new Request();

        $request->merge(
            [
                'username_like' => 'a',
            ]
        );

        $modelfilter = new modelFilters(
            $request
        );

        $users = UsersController::filter_user($modelfilter);

        $users_pure = User::where('username', 'like', '%a%')
            ->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByDateToAndFromAndEmail()
    {
        $this->__intiDb();

        $request = new Request();

        $data = [
            'created_at' => [
                'from' => now()->subDays(10),
                'to'   => now()->addDays(30),
            ],
            'updated_at' => [
                'from' => now()->subDays(10),
                'to'   => now()->addDays(30),
            ],
            'email' => 'mehdifathi.developer@gmail.com',
        ];

        $request->merge(
            $data
        );

        $modelfilter = new  modelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filter_user($modelfilter);

        $users_pure = User::whereBetween(
            'created_at',
            [
                $data['created_at']['from'],
                $data['created_at']['to'],
            ]
        )->whereBetween(
            'updated_at',
            [
                $data['updated_at']['from'],
                $data['updated_at']['to'],
            ]
        )->where('email', $data['email'])
            ->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByDateFrom()
    {
        $this->__intiDb();

        $request = new Request();

        $data = [
            'created_at' => [
                'from' => '2019-01-01 17:11:46',
                'to'   => '2019-02-06 10:11:46',
            ],
        ];

        $request->merge(
            $data
        );

        $modelfilter = new  modelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filter_user($modelfilter);

        $users_pure = User::whereBetween(
            'created_at',
            [
                $data['created_at']['from'],
                $data['created_at']['to'],
            ]
        )->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetGetUserByEmailUsername()
    {
        $this->__intiDb();

        $request = new Request();

        $request->merge(
            [
                'username' => 'mehdi',
                'email'    => 'mehdifathi.developer@gmail.ccom',
            ]
        );

        $modelfilter = new  modelFilters(
            $request
        );

        $users = UsersController::filter_user($modelfilter);

        $users_pure = User::where([
            'username' => 'mehdi',
            'email'    => 'mehdifathi.developer@gmail.ccom',
        ])->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itThrowExceptionWhiteList()
    {
        $this->__intiDb();

        $request = new Request();

        $request->merge(
            [
                'name' => 'mehdi',
            ]
        );

        $modelfilter = new  modelFilters(
            $request
        );

        try {
            $users = UsersController::filter_user($modelfilter);
        } catch (Exception $e) {
            $this->assertEquals(0, $e->getCode());
        }
    }
}
