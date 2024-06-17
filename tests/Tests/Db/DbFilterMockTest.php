<?php

namespace Tests\Tests\Db;

use EloquentFilter\ModelFilter;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Mockery as m;
use Tests\Models\Category;
use Tests\Models\Tag;

class DbFilterMockTest extends \TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->builder = m::mock(Builder::class);
    }

    public function testWhere()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $categories = DB::table('categories')->filter();

        $builder = DB::table('categories')->where('title', 'sport');

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $categories->getBindings());
    }

    public function testWhereWithEloquentFilter()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $categories = DB::table('categories')->filter();

        $builder = DB::table('categories')->where('title', 'sport');

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $categories->getBindings());

        $builder = new Category();

        $builder = $builder->query()->where('title', 'sport');

        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $categories = Category::filter($this->request->query());

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $categories->getBindings());
    }

    public function testWhereEloquentWithDB()
    {

        $builder = new Category();

        $builder = $builder->query()->where('title', 'sport');

        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $categories = Category::filter();

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $categories->getBindings());

        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $categories = DB::table('categories')->filter();

        $builder = DB::table('categories')->where('title', 'sport');

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $categories->getBindings());
    }


    public function testWhereIn()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'username' => ['mehdi', 'ali'],
                'family' => null,
            ]
        );

        $users = DB::table('users')->filter();

        $builder = DB::table('users')
            ->wherein('username', ['mehdi', 'ali']);

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users->getBindings());
    }

    public function testWhereInWithArray()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
            ]
        );

        $users = DB::table('users')->filter([
            'username' => ['mehdi', 'ali'],
            'family' => null,
        ]);

        $builder = DB::table('users')
            ->wherein('username', ['mehdi', 'ali']);

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users->getBindings());
    }

    public function testWhereLike()
    {
        $builder = DB::table('users')->where('email', 'like', '%meh%');
        $this->request->shouldReceive('query')->andReturn(
            [
                'email' => [
                    'like' => '%meh%',
                ],
            ]
        );

        $users = DB::table('users')->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertSame(['%meh%'], $builder->getBindings());
    }

    public function testWhereOr1()
    {
        $builder = DB::table('users')
            ->where('baz', 'boo')
            ->where('count_posts', 22)
            ->orWhere('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'boo',
                'count_posts' => 22,
                'or' => [
                    'baz' => 'joo',
                ],
            ]
        );

        $users = DB::table('users')->filter();

        $users_to_sql = str_replace('(', '', $users->toSql());
        $users_to_sql = str_replace(')', '', $users_to_sql);
        $this->assertSame($users_to_sql, $builder->toSql());
        $this->assertEquals(['boo', 22, 'joo'], $users->getBindings());
    }

    public function testWhereByOpt()
    {
        $builder = DB::table('categories')->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'operator' => '>',
                    'value' => 35,
                ],
            ]
        );

        $categories = DB::table('categories')->filter();

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals([35], $categories->getBindings());
    }

    public function testMaxLimit()
    {

        $builder = DB::table('users')->limit(20);

        $this->request->shouldReceive('query')->andReturn(
            [
                'f_params' => [
                    'limit' => 25,
                ],
            ]
        );

        $users = DB::table('users')->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
    }


    public function testFParamException()
    {
        try {
            $this->request->shouldReceive('query')->andReturn(
                [
                    'f_params' => [
                        'orderBys' => [
                            'field' => 'id',
                            'type' => 'ASC',
                        ],
                    ],
                ]
            );

            DB::table('users')->filter();
        } catch (EloquentFilterException $e) {
            $this->assertEquals(2, $e->getCode());
        }
    }

    public function testExclusiveException()
    {
        $this->expectException(EloquentFilterException::class);

        $this->request->shouldReceive('query')->andReturn(
            [
                'f_params' => [
                    'orderBys11' => [
                        'field' => 'id',
                        'type' => 'ASC',
                    ],
                ],
            ]
        );

        DB::table('users')->filter();
    }


    public function testFParamOrder()
    {
        $builder = DB::table('users')
            ->orderBy('id')
            ->orderBy('count_posts');

        $this->request->shouldReceive('query')->andReturn(
            [
                'f_params' => [
                    'orderBy' => [
                        'field' => 'id,count_posts',
                        'type' => 'ASC',
                    ],
                ],
            ]
        );

        $users = DB::table('users')->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
    }


    //
    // public function testWhereByOptWithTrashed()
    // {
    //     $builder = new Category();
    //
    //     $builder = $builder->newQuery()->withTrashed()
    //         ->where('count_posts', '>', 35);
    //
    //     $this->request->shouldReceive('query')->andReturn(
    //         [
    //             'count_posts' => [
    //                 'operator' => '>',
    //                 'value' => 35,
    //             ],
    //         ]
    //     );
    //
    //     $users = Category::withTrashed()->filter();
    //
    //     $this->assertSame($users->toSql(), $builder->toSql());
    //
    //     $this->assertEquals([35], $users->getBindings());
    // }
    //
    // public function testMessRequest()
    // {
    //     $builder = new User();
    //
    //     $builder = $builder->newQuery()
    //         ->where('username', 'mehdi');
    //
    //     $this->request->shouldReceive('query')->andReturn(
    //         [
    //         ]
    //     );
    //
    //     $data = ['username' => 'mehdi'];
    //
    //     $users = User::filter($data);
    //
    //     $this->assertSame($users->toSql(), $builder->toSql());
    // }

    public function testWhereByOptZero()
    {
        $builder = new Category();

        $builder = DB::table('categories')
            ->where('count_posts', '>', 0);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'operator' => '>',
                    'value' => 0,
                ],
            ]
        );

        $categories = DB::table('categories')->filter();

        $this->assertSame($categories->toSql(), $builder->toSql());

        $this->assertEquals([0], $categories->getBindings());
    }

    public function testWhereBetween()
    {

        $builder = DB::table('tags')->whereBetween(
            'created_at',
            [
                '2019-01-01 17:11:46',
                '2019-02-06 10:11:46',
            ]
        );

        $this->request->shouldReceive('query')->andReturn(
            [
                'created_at' => [
                    'start' => '2019-01-01 17:11:46',
                    'end' => '2019-02-06 10:11:46',
                ],
            ]
        );

        $users = DB::table('tags')->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $builder->getBindings());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $users->getBindings());
    }

    //
    public function testWhereDate()
    {
        $builder = new Tag();

        $builder = DB::table('tags')->whereDate(
            'created_at',
            '2022-07-14',
        );

        $this->request->shouldReceive('query')->andReturn(
            [
                'created_at' =>
                    '2022-07-14'

            ]
        );

        $users = DB::table('tags')->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2022-07-14'], $builder->getBindings());
        $this->assertEquals(['2022-07-14'], $users->getBindings());
    }

    public function testResponseCallback()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $categories = DB::table('categories')->filter()->getResponseFilter(function ($out) {

            $data['data'] = $out;

            return $data;
        });

        $builder = DB::table('categories')->where('title', 'sport');

        $this->assertSame($categories['data']->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $categories['data']->getBindings());
    }

    public function tearDown(): void
    {
        m::close();
    }
}
