<?php

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Illuminate\Http\Request;
use Tests\Controllers\UsersController;
use Tests\Models\User;

class UserFilterTest extends TestCase
{
    /** @test */
    public function itCanGetUserByEmailAndUsername()
    {
        $request = new Request();

        $request->merge(
            [
                'username'   => 'mehdi',
                'email'      => 'mehdifathi.developer@gmail.com',
                'created_at' => '',
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
    public function it_can_get_user_by_a_custom_filter()
    {
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
}
