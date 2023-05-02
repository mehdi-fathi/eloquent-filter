<?php

namespace Tests\Tests;

use eloquentFilter\Facade\EloquentFilter;
use EloquentFilter\ModelFilter;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Mockery as m;
use Tests\Models\Car;
use Tests\Models\Category;
use Tests\Models\CustomDetect\WhereRelationLikeCondition;
use Tests\Models\Order;
use Tests\Models\Stat;
use Tests\Models\Tag;
use Tests\Models\User;

class ModelFilterMockTest extends \TestCase
{
    use Filterable;

    /**
     * @var ModelFilter
     */
    protected $filter;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $testInput;

    /**
     * @var array
     */
    protected $config;

    public $request;
    public $userModel;

    public function setUp(): void
    {
        parent::setUp();
        $this->builder = m::mock(Builder::class);
    }

    protected function getMockModel()
    {
        $model = m::mock(\Tests\Models\User::class);
        $model->shouldReceive('getWhiteListFilter')->andReturn(
            [
                'id',
                'username',
                'family',
                'email',
                'count_posts',
                'created_at',
                'updated_at',
            ]
        );

        return $model;
    }

    protected function makeBuilderWithModel($obj = null)
    {
        if (!empty($obj)) {
            $this->builder->shouldReceive('getModel')->andReturn($obj);
        } else {
            $this->userModel = $this->getMockModel();

            $this->builder->shouldReceive('getModel')->andReturn($this->userModel);
        }
    }

    protected function __initQuery($obj = null)
    {
        $this->makeBuilderWithModel($obj);
    }

    public function testWhere()
    {
        $builder = new Category();

        $builder = $builder->query()
            ->where('title', 'sport');

        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $users = Category::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['sport'], $users->getBindings());
    }

    public function testWhereAlias()
    {
        $builder = new Stat();

        $builder = $builder->query()
            ->where('type', 'mehdi')
            ->where('national_code', 1234);

        $this->request->shouldReceive('query')->andReturn(
            [
                'type' => 'mehdi',
                'code' => 1234,
            ]
        );

        $stats = Stat::filter($this->request->query());

        $this->assertSame($stats['data']->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 1234], $stats['data']->getBindings());
    }

    public function testWhereZero()
    {
        $builder = new Category();

        $builder = $builder->query()
            ->where('count_posts', 0);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => 0,
            ]
        );

        $users = Category::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['0'], $users->getBindings());
    }

    public function testWhereSomeParamNull()
    {
        $builder = new User();

        $builder = $builder->query()
            ->where('username', 'mehdi');

        $this->request->shouldReceive('query')->andReturn(
            [
                'username' => 'mehdi',
                'family' => null,
                'email' => null,
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi'], $users->getBindings());
    }

    public function testRequestNull()
    {
        $builder = new Category();

        $builder = $builder->query();

        $this->request->shouldReceive('query')->andReturn([]);

        $users = Category::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testWhereSomeParamNull2()
    {
        $builder = new User();

        $builder = $builder->query()
            ->where('username', 'mehdi');

        $this->request->shouldReceive('query')->andReturn(
            [
                'username' => 'mehdi',
                'family' => null,
                'created_at' => [
                    'start' => null,
                    'end' => null,
                ],
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi'], $users->getBindings());
    }

    public function testWhereIn()
    {
        $builder = new User();

        $builder = $builder->newQuery()
            ->wherein('username', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn(
            [
                'username' => ['mehdi', 'ali'],
                'family' => null,
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users->getBindings());
    }

    public function testWhereByOpt()
    {
        $builder = new Category();

        $builder = $builder->newQuery()
            ->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'operator' => '>',
                    'value' => 35,
                ],
            ]
        );

        $users = Category::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([35], $users->getBindings());
    }

    public function testWhereByOptWithTrashed()
    {
        $builder = new Category();

        $builder = $builder->newQuery()->withTrashed()
            ->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'operator' => '>',
                    'value' => 35,
                ],
            ]
        );

        $users = Category::withTrashed()->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([35], $users->getBindings());
    }

    public function testMessRequest()
    {
        $builder = new User();

        $builder = $builder->newQuery()
            ->where('username', 'mehdi');

        $this->request->shouldReceive('query')->andReturn(
            [
            ]
        );

        $data = ['username' => 'mehdi'];

        $users = User::filter($data);

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testWhereByOptZero()
    {
        $builder = new Category();

        $builder = $builder->newQuery()
            ->where('count_posts', '>', 0);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'operator' => '>',
                    'value' => 0,
                ],
            ]
        );

        $users = Category::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([0], $users->getBindings());
    }

    //
    public function testWhereBetween()
    {
        $builder = new Tag();

        $builder = $builder->whereBetween(
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

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $builder->getBindings());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $users->getBindings());
    }

    public function testWhereDate()
    {
        $builder = new Tag();

        $builder = $builder->query()->whereDate(
            'created_at',
            '2022-07-14',
        );

        $this->request->shouldReceive('query')->andReturn(
            [
                'created_at' =>
                    '2022-07-14'

            ]
        );

        $users = Tag::filter($this->request->query());


        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2022-07-14'], $builder->getBindings());
        $this->assertEquals(['2022-07-14'], $users->getBindings());
    }


    public function testExceptionOneValueSetWhiteList()
    {
        try {
            $this->request->shouldReceive('query')->andReturn(
                [
                    'role' => [
                        'admin', 'user',
                    ],
                ]
            );

            Tag::filter($this->request->query());
        } catch (EloquentFilterException $e) {
            $this->assertEquals(1, $e->getCode());
        }
    }

    public function testExceptionIsThereAnyWhiteList()
    {
        try {
            $this->request->shouldReceive('query')->andReturn(
                [
                    'role' => [
                        'admin', 'user',
                    ],
                ]
            );

            Order::filter($this->request->query());
        } catch (EloquentFilterException $e) {
            $this->assertEquals(1, $e->getCode());
        }
    }

    public function testFParamOrder()
    {
        $builder = new Tag();

        $builder = $builder->newQuery()
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

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testFParamLimit()
    {
        $builder = new Tag();

        $builder = $builder->newQuery()
            ->limit(5);

        $this->request->shouldReceive('query')->andReturn(
            [
                'f_params' => [
                    'limit' => 5,
                ],
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testMaxLimit()
    {
        $builder = new Tag();

        $builder = $builder->newQuery()
            ->limit(20);

        $this->request->shouldReceive('query')->andReturn(
            [
                'f_params' => [
                    'limit' => 25,
                ],
            ]
        );

        $users = Tag::filter($this->request->query());

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

            Tag::filter($this->request->query());
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

        Tag::filter($this->request->query());
    }

    public function testAddWhiteList()
    {
        $userModel2 = m::mock(User::class);
        $userModel2->shouldReceive('getWhiteListFilter')->andReturn(
            [
                'username',
                'baz',
                'too',
                'count_posts',
                'foo.bam',
                'foo.created_at',
                'foo.baz.bam',
                'created_at',
                'email',
                'name',
            ]
        );

        $user_model = new User();
        $user_model->addWhiteListFilter('name');

        $this->assertEquals($user_model->getWhiteListFilter(), $userModel2->getWhiteListFilter());
    }

    public function testWhereHasRelationOneNestedModel()
    {
        $builder = new Tag();

        $builder = $builder->whereHas(
            'foo',
            function ($q) {
                $q->where('bam', 'qux');
            }
        )->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'foo' => [
                    'bam' => 'qux',
                ],
                'baz' => 'joo',
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'joo'], $users->getBindings());
    }

    public function testWhereHasRelationTwoNested()
    {
        $builder = new Tag();

        $builder = $builder->whereHas(
            'foo.baz',
            function ($q) {
                $q->where('bam', 'qux');
            }
        )->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'foo' => [
                    'baz' => [
                        'bam' => 'qux',
                    ],
                ],
                'baz' => 'joo',
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'joo'], $users->getBindings());
    }

    public function testWhereHasRelationThereNested()
    {
        $builder = new Tag();

        $builder = $builder->whereHas(
            'foo.baz',
            function ($q) {
                $q->where('bam', 'qux');
            }
        )->whereHas(
            'foo',
            function ($q) {
                $q->where('bam', 'boom');
            }
        )->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'foo' => [
                    'baz' => [
                        'bam' => 'qux',
                    ],
                    'bam' => 'boom',
                ],
                'baz' => 'joo',
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'boom', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'boom', 'joo'], $users->getBindings());
    }

    public function testWhereInSql()
    {
        $builder = new Tag();

        $builder = $builder->whereIn('baz', ['boom', 'joe', null]);

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => [
                    'boom', 'joe', null,
                ],
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['boom', 'joe', null], $builder->getBindings());
        $this->assertEquals(['boom', 'joe', null], $users->getBindings());
    }

    public function testNullReqeust()
    {
        $this->__initQuery();

        $this->request->shouldReceive('query')->andReturn(null);

        $builder = new Category();

        $users = Category::filter($this->request->query());

        $this->assertEquals($users->toSql(), $builder->toSql());
    }

    public function testNullArrReqeust()
    {
        $this->__initQuery();

        $this->request->shouldReceive('query')->andReturn(
            [
            ]
        );

        $builder = new Category();

        $users = Category::filter($this->request->query());

        $this->assertEquals($users->toSql(), $builder->toSql());
    }

    public function testWhereIgnoreParam()
    {
        $builder = new Tag();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
                'google_index' => true,
                'is_payment' => true,
            ]
        );

        $users = Tag::ignoreRequest(
            [
                'google_index',
                'is_payment',
            ]
        )->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());
    }

    public function testWhereIgnoreParamThatNotExistRequest()
    {
        $builder = new Tag();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
                'google_index' => true,
            ]
        );

        $users = Tag::ignoreRequest(
            [
                'google_index',
                'is_payment_paypal',
            ]
        )->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());
    }

    public function testFilterRequests()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
            ]
        );
        $this->assertSame($this->request->query(), EloquentFilter::filterRequests());
    }

    public function testIgnoreRequest()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
                'google_index' => true,
                'is_payment' => true,
            ]
        );

        $users = Tag::ignoreRequest(
            [
                'google_index',
                'is_payment',
            ]
        )->filter($this->request->query());

        $this->assertSame(
            [
                'google_index',
                'is_payment',
            ],
            EloquentFilter::getIgnoredRequest()
        );
    }

    public function testGetInjectedDetections()
    {
        $builder = new User();

        $builder = $builder->query()
            ->whereHas(
                'foo',
                function ($q) {
                    $q->where('bam', 'like', '%mehdi%');
                }
            )
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => [
                    'value' => 'boo',
                    'limit' => 10,
                    'email' => 'mehdifathi',
                    'like_relation_value' => 'mehdi',
                ],
                'count_posts' => 10,
            ]
        );

        $users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();

        $this->assertEquals([WhereRelationLikeCondition::class], EloquentFilter::getInjectedDetections());

    }

    public function testFilterRequestsIndex()
    {
        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
            ]
        );
        $this->assertSame($this->request->query()['baz'], EloquentFilter::filterRequests('baz'));
    }

    public function testWhereOr1()
    {
        $builder = new Tag();

        $builder = $builder->query()
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

        $users = Tag::filter($this->request->query());

        $users_to_sql = str_replace('(', '', $users->toSql());
        $users_to_sql = str_replace(')', '', $users_to_sql);
        $this->assertSame($users_to_sql, $builder->toSql());
        $this->assertEquals(['boo', 22, 'joo'], $users->getBindings());
    }

    public function testWhereByOpt1()
    {
        $builder = new Tag();

        $builder = $builder->where('count_posts', 345);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => 345,
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals([345], $builder->getBindings());
        $this->assertEquals([345], $users->getBindings());
    }

    public function testWhereIn2()
    {
        $builder = new User();

        $builder = $builder->query()->whereIn('username', ['mehdi22', 'ali22'])
            ->where('name', 'mehdi');

        $this->request->shouldReceive('query')->andReturn(
            [
                'username' => ['mehdi22', 'ali22'],
                'name' => 'mehdi',
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi22', 'ali22', 'mehdi'], $users->getBindings());
    }

    public function testWhereByOpt2()
    {
        $builder = new Tag();

        $builder = $builder->query()->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'operator' => '>',
                    'value' => 35,
                ],
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([35], $users->getBindings());
    }

    public function testWhereHasWhereInRelationOneNestedModel()
    {
        $builder = new Tag();

        $builder = $builder->whereHas(
            'foo',
            function ($q) {
                $q->whereIn('bam', ['qux', 'mehdi']);
            }
        )->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'foo' => [
                    'bam' => ['qux', 'mehdi'],
                ],
                'baz' => 'joo',
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'mehdi', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'mehdi', 'joo'], $users->getBindings());
    }

    public function testWhereHasInRelationThereNested()
    {
        $builder = new Tag();

        $builder = $builder->whereHas(
            'foo.baz',
            function ($q) {
                $q->whereIn('bam', ['qux', 'mehdi']);
            }
        )->whereHas(
            'foo',
            function ($q) {
                $q->whereIn('bam', ['boom', 'noon']);
            }
        )->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'foo' => [
                    'baz' => [
                        'bam' => ['qux', 'mehdi'],
                    ],
                    'bam' => ['boom', 'noon'],
                ],
                'baz' => 'joo',
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'mehdi', 'boom', 'noon', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'mehdi', 'boom', 'noon', 'joo'], $users->getBindings());
    }

    public function testWhereBetweenWithEmailCountPosts()
    {
        $builder = new User();

        $builder = $builder->whereBetween(
            'created_at',
            [
                '2019-01-01 17:11:46',
                '2019-02-06 10:11:46',
            ]
        )->where('email', 'mehdifathi.developer@gmail.com')
            ->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn(
            [
                'created_at' => [
                    'start' => '2019-01-01 17:11:46',
                    'end' => '2019-02-06 10:11:46',
                ],
                'email' => 'mehdifathi.developer@gmail.com',
                'count_posts' => [
                    'operator' => '>',
                    'value' => 35,
                ],
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46', 'mehdifathi.developer@gmail.com', '35'], $builder->getBindings());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46', 'mehdifathi.developer@gmail.com', '35'], $users->getBindings());
    }

    public function testWhereBetweenWithZero()
    {
        $builder = new User();

        $builder = $builder->whereBetween(
            'count_posts',
            [
                0,
                200,
            ]
        )->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => [
                    'start' => 0,
                    'end' => 200,
                ],
                'email' => 'mehdifathi.developer@gmail.com',
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals([0, 200, 'mehdifathi.developer@gmail.com'], $builder->getBindings());
        $this->assertEquals([0, 200, 'mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testWhereLike1()
    {
        $builder = new Tag();

        $builder = $builder->query()->where('email', 'like', '%meh%');
        $this->request->shouldReceive('query')->andReturn(
            [
                'email' => [
                    'like' => '%meh%',
                ],
            ]
        );

        $users = Tag::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertSame(['%meh%'], $builder->getBindings());
    }

    public function testSetDetection()
    {
        $builder = new User();

        $builder = $builder->query()
            ->whereHas(
                'foo',
                function ($q) {
                    $q->where('bam', 'like', '%mehdi%');
                }
            )
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => [
                    'value' => 'boo',
                    'limit' => 10,
                    'email' => 'mehdifathi',
                    'like_relation_value' => 'mehdi',
                ],
                'count_posts' => 10,
            ]
        );

        $users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['%mehdi%', 'boo', '%mehdifathi%', 10], $users->getBindings());
    }

    public function testEloquentFilterCustomDetection()
    {
        $builder = new User();

        $builder = $builder->query()
            ->whereHas(
                'foo',
                function ($q) {
                    $q->where('bam', 'like', '%mehdi%');
                }
            )
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => [
                    'value' => 'boo',
                    'limit' => 10,
                    'email' => 'mehdifathi',
                    'like_relation_value' => 'mehdi',
                ],
                'count_posts' => 10,
            ]
        );

        $users = User::filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['%mehdi%', 'boo', '%mehdifathi%', 10], $users->getBindings());
    }

    public function testSetDetection1()
    {
        $builder = new User();

        $builder = $builder->query()
            ->where('count_posts', '=', 10)
            ->where('baz', '=', []);

        $this->request->shouldReceive('query')->andReturn(
            [
                'count_posts' => 10,
                'baz' => [
                    'value' => 'boo',
                    'limit' => 10,
                    'email' => 'mehdifathi',
                    'like_relation_value' => 'mehdi',
                ],
            ]
        );

        $users = User::setLoadInjectedDetection(false)->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testAcceptRequest()
    {
        $builder = new Tag();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
                'google_index' => true,
                'gmail_api' => 'dfsmfjkvx#$cew45',
            ]
        );

        $users = Tag::acceptRequest(
            [
                'baz',
            ]
        )->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());

        $this->assertEquals(['baz' => 'joo'], EloquentFilter::getAcceptedRequest());
    }

    public function testSetBlackListDetection()
    {
        $builder = new Tag();

        $builder = $builder->newQuery()->wherein('email', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn(
            [
                'email' => ['mehdi', 'ali'],
                'baz' => 'joo', //should be omited on whereCondition
            ]
        );

        $users = Tag::setBlackListDetection(
            [
                'WhereCondition',
            ]
        )->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testSetBlackListDetectionDefault()
    {
        $builder = new Car();

        $builder = $builder->newQuery()->wherein('name', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn(
            [
                'name' => ['mehdi', 'ali'],
                'model' => 'joo', //should be omited on whereCondition
            ]
        );

        $users = Car::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testAcceptRequest2()
    {
        $builder = new Tag();

        $this->request->shouldReceive('query')->andReturn(
            [
                'google_index' => 'joo',
                'gmail_api' => 'joo',
                'baz' => 'joo',
            ]
        );

        $users = Tag::AcceptRequest(
            [
                'show_new_users',
            ]
        )->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals([], $builder->getBindings());
        $this->assertEquals([], $users->getBindings());

        $this->assertEquals(['show_new_users'], EloquentFilter::getAcceptedRequest());
    }

    public function testAcceptRequestNull()
    {
        $builder = new Tag();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
            ]
        );

        $users = Tag::AcceptRequest([])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());

        $this->assertEquals(null, EloquentFilter::getAcceptedRequest());
    }

    public function testSerializeRequestFilter()
    {
        $builder = new Category();

        $builder = $builder->newQuery()->wherein('title', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn(
            [
                'new_title' => ['__mehdi__', '__ali__'],
                'family' => null,
            ]
        );

        $users = Category::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users->getBindings());
    }

    public function testResponseFilter()
    {
        $builder = new Stat();

        $builder = $builder->newQuery()->wherein('type', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn(
            [
                'type' => ['mehdi', 'ali'],
                'family' => null,
            ]
        );

        $users = Stat::filter($this->request->query());

        $this->assertSame($users['data']->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users['data']->getBindings());
    }

    public function testRequestFilterKeyFilledConfig()
    {
        config(['eloquentFilter.request_filter_key' => 'filter']);

        $builder = new User();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'filter' => [
                    'email' => 'mehdifathi.developer@gmail.com',
                ],
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testFalseEnabledConfig()
    {
        config(['eloquentFilter.enabled' => false]);

        $builder = new User();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'filter' => [
                    'email' => 'mehdifathi.developer@gmail.com',
                ],
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertNotSame($users->toSql(), $builder->toSql());

        $this->assertNotEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
        $this->assertFalse($users->isUsedEloquentFilter());
    }

    public function testFalseEnabledConfigNotBreakQuery()
    {
        config(['eloquentFilter.enabled' => false]);

        $builder = new User();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'filter' => [
                    'email' => 'new_memeber@gmail.com',
                ],
            ]
        );

        $users = User::filter($this->request->query())
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testWhereBetweenWithRelation()
    {
        $builder = new User();

        $builder = $builder->query()
            ->select('eloquent_builder_test_model_new_strategy_stubs.name')
            ->join('foo', 'foo.user_id', '=', 'eloquent_builder_test_model_new_strategy_stubs.id')
            ->whereBetween(
                'foo.created_at',
                [
                    '2019-01-01 17:11:46',
                    '2019-02-06 10:11:46',
                ]
            );

        $this->request->shouldReceive('query')->andReturn(['foo' => ['created_at' => ['start' => '2019-01-01 17:11:46', 'end' => '2019-02-06 10:11:46']]]); //delete it

        $users = User::select('eloquent_builder_test_model_new_strategy_stubs.name')->
        join('foo', 'foo.user_id', '=', 'eloquent_builder_test_model_new_strategy_stubs.id')
            ->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $users->getBindings());
    }

    public function testIgnoreRequestConfig()
    {
        config(['eloquentFilter.ignore_request' => ['show_query', 'new_trend']]);

        $builder = new User();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'email' => 'mehdifathi.developer@gmail.com',
                'show_query' => true,
                'new_trend' => '2021',
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testIgnoreRequestBeforeSetConfig()
    {
        config(['eloquentFilter.ignore_request' => ['show_query', 'new_trend']]);

        $builder = new User();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'email' => 'mehdifathi.developer@gmail.com',
                'id' => 99,
                'show_query' => true,
                'new_trend' => '2021',
            ]
        );

        $users = User::ignoreRequest(['id'])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testTrueEnabledConfig()
    {
        config(['eloquentFilter.enabled' => true]);

        $builder = new User();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn(
            [
                'email' => 'mehdifathi.developer@gmail.com',
            ]
        );

        $users = User::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testFalseEnabledCustomDetectionConfig()
    {
        config(['eloquentFilter.enabled_custom_detection' => false]);

        $builder = new User();

        $builder = $builder->query()
            ->whereHas(
                'foo',
                function ($q) {
                    $q->where('bam', 'like', '%mehdi%');
                }
            )
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => [
                    'value' => 'boo',
                    'limit' => 10,
                    'email' => 'mehdifathi',
                    'like_relation_value' => 'mehdi',
                ],
                'count_posts' => 10,
            ]
        );

        $users = User::filter();

        $this->assertNotSame($users->toSql(), $builder->toSql());
        $this->assertNotEquals(['%mehdi%', 'boo', '%mehdifathi%', 10], $users->getBindings());
        $this->assertNull(EloquentFilter::getInjectedDetections());
    }

    public function testCustomFilter()
    {
        $builder = new Category();

        $builder = $builder->query()->where('title', 'like', '%mehdi%');

        $this->request->shouldReceive('query')->andReturn(
            [
                'sample_like' => 'mehdi',
            ]
        );

        $categories = Category::filter();

        $this->assertSame($categories->toSql(), $builder->toSql());
        $this->assertEquals(['%mehdi%'], $categories->getBindings());
    }

    public function testAddWhereToFilter()
    {
        $builder = new Category();

        $builder = $builder->query()
            ->where('title', 'sport')
            ->where('count_posts', '>', 10);

        $this->request->shouldReceive('query')->andReturn(
            [
                'title' => 'sport',
            ]
        );

        $users = Category::filter($this->request->query())->where('count_posts', '>', 10);

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['sport', 10], $users->getBindings());
    }

    public function testMacros()
    {
        $builder = new User();

        $builder = $builder->query()
            ->whereHas(
                'foo',
                function ($q) {
                    $q->where('bam', 'like', '%mehdi%');
                }
            )
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => [
                    'value' => 'boo',
                    'limit' => 10,
                    'email' => 'mehdifathi',
                    'like_relation_value' => 'mehdi',
                ],
                'count_posts' => 10,
            ]
        );

        $users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();

        $this->assertEquals([WhereRelationLikeCondition::class], $users->getDetectionsInjected());
        $this->assertTrue($users->isUsedEloquentFilter());

    }

    public function tearDown(): void
    {
        m::close();
    }
}
