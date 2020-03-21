<?php

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;
use Tests\Controllers\UsersController;
use Tests\Models\User;
use Tests\Seeds\PostTableSeeder;
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

        $seeder = new PostTableSeeder();
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
        $users = UsersController::filterUser($modelFilter)->get();
//        dd(DB::getQueryLog());
        $users_pure = User::where([
            'family' => 'ahmadi',
        ])->wherein('username', ['ali', 'ali22'])->get();
        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByFamilyAndUsernamesPaginate()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => [
                    'ali',
                    'ali22',
                ],
                'family'  => 'ahmadi',
                'page'    => 1,
                'perpage' => 2,
            ]
        );

        $modelFilter = new ModelFilters(
            $this->request
        );

        $perpage = $this->request['perpage'];
        unset($this->request['perpage']);
        $users = UsersController::filterUser($modelFilter)->paginate($perpage, ['*'], 'page');

        $users_pure = User::where([
            'family' => 'ahmadi',
        ])->wherein('username', ['ali', 'ali22'])->paginate($perpage, ['*'], 'page');

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserPaginate()
    {
        $this->__init();
        $this->request->merge(
            [
                'page'    => 2,
                'perpage' => 2,
            ]
        );

        $modelFilter = new ModelFilters(
            $this->request
        );

        $perpage = $this->request['perpage'];
        unset($this->request['perpage']);
        $users = UsersController::filterUser($modelFilter)->paginate($perpage, ['*'], 'page', $this->request['page']);

        $users_pure = User::paginate($perpage, ['*'], 'page', $this->request['page']);

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
        $users = UsersController::filterUser($modelfilter)->get();
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
        $users = UsersController::filterUser($modelFilter)->get();
        $users_pure = User::where('username', 'like', '%a%')
            ->get();
        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserPostsByCustomfilter()
    {
        $this->__init();
        $this->request->merge(
            [
                'username_like' => 'mehdi',
            ]
        );
        $modelFilter = new ModelFilters(
            $this->request
        );
        $users = UsersController::filterUserWith($modelFilter, ['Posts'])->get();

        $users_pure = User::with('Posts')->where('username', 'like', '%mehdi%')
            ->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanGetUserByDateToAndFromAndEmail()
    {
        $this->__init();
        $data = [
            'created_at' => [
                'start' => now()->subDays(10)->format('Y/m/d'),
                'end'   => now()->addDays(30)->format('Y/m/d'),
            ],
            'updated_at' => [
                'start' => now()->subDays(10)->format('Y/m/d'),
                'end'   => now()->addDays(30)->format('Y/m/d'),
            ],
            'email' => 'mehdifathi.developer@gmail.com',
        ];
        $this->request->merge(
            $data
        );
        $modelfilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelfilter)->get();
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

        $users = UsersController::filterUser($modelFilter)->get();
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
        $users = UsersController::filterUser($modelFilter)->get();
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
        $users = UsersController::filterUser($modelFilter)->get();
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
        $users = UsersController::filterUser($modelFilter)->get();
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
        $users = UsersController::filterUser($modelFilter)->get();
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
        $users = UsersController::filterUser($modelFilter)->get();
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
            $users = UsersController::filterUser($modelFilter)->get();
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
                    'limit'  => 1,
                ],
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter)->get();
        $users_pure = User::where([
            'username' => 'ahmad',
        ])->limit(1)->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanOrderByIdListUsername()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => 'ahmad',
                'f_params' => [
                    'orderBy'  => [
                        'field' => 'id',
                        'type'  => 'ASC',
                    ],
                ],
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter)->get();
        $users_pure = User::where([
            'username' => 'ahmad',
        ])->orderBy('id', 'ASC')->get();

        $this->assertEquals($users_pure, $users);
    }

    /** @test */
    public function itCanOrderByIdLimitListUsername()
    {
        $this->__init();
        $this->request->merge(
            [
                'username' => 'ahmad',
                'f_params' => [
                    'orderBy'  => [
                        'field' => 'id',
                        'type'  => 'ASC',
                    ],
                    'limit'  => 1,
                ],
            ]
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter)->get();
        $users_pure = User::where([
            'username' => 'ahmad',
        ])->orderBy('id', 'ASC')->limit(1)->get();

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
                        'type'  => 'ASC',
                    ],
                ],
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
    public function itCanGetUserByJallaliDateFrom()
    {
        $this->__init();
        $data = [
            'created_at' => [
                'start' => '1397/10/11 10:11:46',
                'end'   => '1397/11/17 10:11:46',
            ],
        ];
        $this->request->merge(
            $data
        );
        $modelFilter = new  ModelFilters(
            $this->request
        );
        $users = UsersController::filterUser($modelFilter)->get();
        $data['created_at']['start'] = CalendarUtils::createCarbonFromFormat('Y/m/d h:i:s', $data['created_at']['start'])->format('Y-m-d h:i:s');
        $data['created_at']['end'] = CalendarUtils::createCarbonFromFormat('Y/m/d h:i:s', $data['created_at']['end'])->format('Y-m-d h:i:s');
        $users_pure = User::whereBetween(
            'created_at',
            [
                $data['created_at']['start'],
                $data['created_at']['end'],
            ]
        )->get();

        $this->assertEquals($users_pure, $users);
    }
}
