<?php

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Illuminate\Http\Request;
use Tests\Controllers\UsersController;
use Tests\Models\User;
use Tests\Seeds\UserTableSeeder;

/**
 * Class UserFilterTest.
 */
class UserFilterTest extends TestCase
{
    public $request;

    private function __init()
    {
        $seeder = new UserTableSeeder();
        $seeder->run();
        $this->request = new Request();
    }

    /** @test */
    public function itCanGetUserByFamilyAndUsernames()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => [
                    'ali',
                    'ali22',
                ],
                'family' => 'ahmadi',
            ]
        );
        $modelFilter = new ModelFilters(
            $this->request
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
        $this->__init();
        $this->request->merge(
            [
                'username' => 'mehdi',
                'email'    => 'mehdifathi.developer@gmail.com',
            ]
        );
        $modelfilter = new ModelFilters(
            $this->request
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
        $this->__init();
        $this->request->merge(
            [
                'username_like' => 'a',
            ]
        );
        $modelFilter = new ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where('username', 'like', '%a%')
            ->get();
        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByDateToAndFromAndEmail()
    {
        $this->__init();
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
        $this->request->merge(
            $data
        );
        $modelfilter = new  ModelFilters(
            $this->request
        );
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
        $this->__init();
        $data = [
            'count_posts' => [
                'operator' => '>',
                'value'    => 35,
            ],
        ];
        $this->request->merge(
            $data
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );

        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where('count_posts', '>', 35)
            ->get();
        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUsersByNameSetWhiteList()
    {
        $this->__init();
        User::setWhiteListFilter(['name']);
        $data = [
            'name' => 'ali',
        ];
        $this->request->merge(
            $data
        );

        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where('name', 'ali')
            ->get();
        $this->assertEquals($users_pure, $users);
        $arrayWhiteListFilter = [
            'id',
            'username',
            'family',
            'email',
            'count_posts',
            'created_at',
            'updated_at',
        ];
        User::setWhiteListFilter($arrayWhiteListFilter);
    }

    /** @test */
    public function itCanGetUsersByNameWhiteList()
    {
        $this->__init();
        User::addWhiteListFilter('name');
        $data = [
            'name' => 'ali',
        ];
        $this->request->merge(
            $data
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where('name', 'ali')
            ->get();
        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUsersIsNot()
    {
        $this->__init();
        $data = [
            'username' => [
                'operator' => '!=',
                'value'    => 'ali',
            ],
        ];
        $this->request->merge(
            $data
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where('username', '!=', 'ali')
            ->get();
        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByDateFrom()
    {
        $this->__init();
        $data = [
            'created_at' => [
                'start' => '2019-01-01 17:11:46',
                'end'   => '2019-02-06 10:11:46',
            ],
        ];
        $this->request->merge(
            $data
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
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
        $this->__init();
        $this->request->merge(
            [
                'username' => 'mehdi',
                'email'    => 'mehdifathi.developer@gmail.ccom',
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
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
        $this->__init();
        $this->request->merge(
            [
                'role' => 'admin',
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );

        try {
            $users = UsersController::filterUser($modelFilter);
        } catch (Exception $e) {
            $this->assertEquals(0, $e->getCode());
        }
    }
    /** @test */
    public function itCanLimitListUsername()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => 'ahmad',
                'f_params' => [
                    'limit'  => 1
                ]
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where([
            'username' => 'ahmad',
        ])->limit(1)->get();

        $this->assertEquals($users_pure, $users);
    }
    /** @test */
    public function itCanOrderbyIdListUsername()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => 'ahmad',
                'f_params' => [
                    'orderBy'  => [
                       'field' => 'id',
                       'type' => 'ASC',
                    ]
                ]
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter);
        $users_pure = User::where([
            'username' => 'ahmad',
        ])->orderBy('id', 'ASC')->get();

        $this->assertEquals($users_pure, $users);
    }
    /** @test */
    public function itCanThrowExceptionOrderbyIdListUsername()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => 'ahmad',
                'f_params' => [
                    'orderBys'  => [
                        'field' => 'id',
                        'type' => 'ASC',
                    ]
                ]
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );

        try {
            $users = UsersController::filterUser($modelFilter);
        } catch (Exception $e) {
            $this->assertEquals(0, $e->getCode());
        }
    }
}
