<?php

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function itCanGetUserByFamilyAndUsernames()
    {
        $this->__intiDb();

        $request = new Request();

        $request->merge(
            [
                'username' => [
                    'ali',
                    'ali22',
                ],
                'family'    => 'ahmadi',
            ]
        );

        $modelFilter = new ModelFilters(
            $request
        );

//        DB::enableQueryLog(); // Enable query log
        $users = UsersController::filterUser($modelFilter);
//        dd(DB::getQueryLog());

        $users_pure = User::where([
            'family' => 'ahmadi',
        ])->wherein('username', ['ali', 'ali22'])->get();

        $this->assertEquals($users_pure, $users);
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
        $modelfilter = new ModelFilters(
            $request
        );
        $users = UsersController::filterUser($modelfilter);
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

        $modelFilter = new ModelFilters(
            $request
        );

        $users = UsersController::filterUser($modelFilter);

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
                'start' => now()->subDays(10),
                'end'   => now()->addDays(30),
            ],
            'updated_at' => [
                'start' => now()->subDays(10),
                'end'   => now()->addDays(30),
            ],
            'email' => 'mehdifathi.developer@gmail.com',
        ];

        $request->merge(
            $data
        );

        $modelfilter = new  ModelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filterUser($modelfilter);

        $users_pure = User::whereBetween(
            'created_at',
            [
                $data['created_at']['start'],
                $data['created_at']['end'],
            ]
        )->whereBetween(
            'updated_at',
            [
                $data['updated_at']['start'],
                $data['updated_at']['end'],
            ]
        )->where('email', $data['email'])
            ->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUsersThatHasGreaterThan35CountPosts()
    {
        $this->__intiDb();

        $request = new Request();

        $data = [
            'count_posts' => [
                'operator' => '>',
                'value'    => 35,
            ],
        ];

        $request->merge(
            $data
        );

        $modelFilter = new  ModelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filterUser($modelFilter);

        $users_pure = User::where('count_posts', '>', 35)
            ->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUsersByNameWhiteList()
    {
        $this->__intiDb();

        User::setWhiteListFilter('name');

        $request = new Request();

        $data = [
            'name' => 'ali'
        ];

        $request->merge(
            $data
        );

        $modelFilter = new  ModelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filterUser($modelFilter);

        $users_pure = User::where('name', 'ali')
            ->get();

        $this->assertEquals($users_pure, $users);
    }
    /** @test */
    public function itCanGetUsersIsNot()
    {
        $this->__intiDb();

        $request = new Request();

        $data = [
            'username' => [
                'operator' => '!=',
                'value'    => 'ali',
            ],
        ];

        $request->merge(
            $data
        );

        $modelFilter = new  ModelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filterUser($modelFilter);

        $users_pure = User::where('username', '!=', 'ali')
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
                'start' => '2019-01-01 17:11:46',
                'end'   => '2019-02-06 10:11:46',
            ],
        ];

        $request->merge(
            $data
        );

        $modelFilter = new  ModelFilters(
            $request
        );

        DB::connection()->enableQueryLog();

        $users = UsersController::filterUser($modelFilter);

        $users_pure = User::whereBetween(
            'created_at',
            [
                $data['created_at']['start'],
                $data['created_at']['end'],
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

        $modelFilter = new  ModelFilters(
            $request
        );

        $users = UsersController::filterUser($modelFilter);

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
                'role' => 'admin',
            ]
        );

        $modelFilter = new  ModelFilters(
            $request
        );

        try {
            $users = UsersController::filterUser($modelFilter);
        } catch (Exception $e) {
            $this->assertEquals(0, $e->getCode());
        }
    }
}
