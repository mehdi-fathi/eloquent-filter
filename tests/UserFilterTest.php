<?php

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
    public function testFilterFindUser()
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

        $this->assertEquals($users_pure, $users);
    }

    public function testFilterFindUserDate()
    {

        $request = new \Illuminate\Http\Request();

        $data = [
            'created_at' => [
                'from' => now()->subDays(10),
                'to' => now()->addDays(30),
            ],
            'updated_at' => [
                'from' => now()->subDays(10),
                'to' => now()->addDays(30),
            ],
            'email' => 'mehdifathi.developer@gmail.com'
        ];

        $request->merge(
                $data
        );

        $modelfilter = new  \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = \Tests\Controllers\Users::filter_user($modelfilter);

        $users_pure = \Tests\Models\User::whereBetween(
            'created_at',
            [
                $data['created_at']['from'],
                $data['created_at']['to']
            ]
        )->whereBetween(
            'updated_at',
            [
                $data['updated_at']['from'],
                $data['updated_at']['to']
            ]
        )->where('email', $data['email'])
            ->orderByDesc('id')->get();

        $this->assertEquals($users_pure, $users);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testFilterNotFindUser()
    {

        $request = new \Illuminate\Http\Request();

        $request->merge(
            [
                'username' => 'mehdi',
                'email' => 'mehdifathi.developer@gmail.ccom'
            ]
        );

        $modelfilter = new  \eloquentFilter\QueryFilter\modelFilters\modelFilters(
            $request
        );

        $users = \Tests\Controllers\Users::filter_user($modelfilter);

        $users_pure = \Tests\Models\User::where([
            'username' => 'mehdi',
            'email' => 'mehdifathi.developer@gmail.ccom'
        ])->get();

        $this->assertEquals($users_pure, $users);
    }
}
