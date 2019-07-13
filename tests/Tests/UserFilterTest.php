<?php

class UserFilterTest extends TestCase
{

    public function testFilterFindUser()
    {
        $request = new \Illuminate\Http\Request();

        $request->merge(
            [
                'username'   => 'mehdi',
                'email'      => 'mehdifathi.developer@gmail.com',
                'created_at' => '',
            ]
        );

        $modelfilter = new \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        $users = \Tests\Controllers\UsersController::filter_user($modelfilter);

        $users_pure = \Tests\Models\User::where([
            'username' => 'mehdi',
            'email'    => 'mehdifathi.developer@gmail.com',
        ])->get();

        $this->assertEquals($users_pure, $users);
    }

    public function testFilterFindUserWithCustomFilter()
    {
        $request = new \Illuminate\Http\Request();

        $request->merge(
            [
                'username_like' => 'a',
            ]
        );

        $modelfilter = new \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        $users = \Tests\Controllers\UsersController::filter_user($modelfilter);

//        dd($users);

        $users_pure = \Tests\Models\User::where('username', 'like', '%a%')
            ->get();

        $this->assertEquals($users_pure, $users);
    }

    public function testFilterFindUserDate()
    {
        $request = new \Illuminate\Http\Request();

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

        $modelfilter = new  \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = \Tests\Controllers\UsersController::filter_user($modelfilter);

        $users_pure = \Tests\Models\User::whereBetween(
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

    public function testFilterJustFindUserDate()
    {
        $request = new \Illuminate\Http\Request();

        $data = [
            'created_at' => [
                'from' => '2019-01-01 17:11:46',
                'to'   => '2019-02-06 10:11:46',
            ],
        ];

        $request->merge(
            $data
        );

        $modelfilter = new  \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = \Tests\Controllers\UsersController::filter_user($modelfilter);

        $users_pure = \Tests\Models\User::whereBetween(
            'created_at',
            [
                $data['created_at']['from'],
                $data['created_at']['to'],
            ]
        )->get();

        $this->assertEquals($users_pure, $users);
    }

    public function testFilterNotFindUser()
    {
        $request = new \Illuminate\Http\Request();

        $request->merge(
            [
                'username' => 'mehdi',
                'email'    => 'mehdifathi.developer@gmail.ccom',
            ]
        );

        $modelfilter = new  \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        $users = \Tests\Controllers\UsersController::filter_user($modelfilter);

        $users_pure = \Tests\Models\User::where([
            'username' => 'mehdi',
            'email'    => 'mehdifathi.developer@gmail.ccom',
        ])->get();

        $this->assertEquals($users_pure, $users);
    }
}
