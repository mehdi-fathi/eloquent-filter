<?php

namespace Tests\Tests;

use eloquentFilter\Facade\EloquentFilter;
use EloquentFilter\ModelFilter;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery as m;
use Tests\Models\CustomDetect\WhereRelationLikeCondition;
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
        $model->shouldReceive('getWhiteListFilter')->andReturn([
            'id',
            'username',
            'family',
            'email',
            'count_posts',
            'created_at',
            'updated_at',
        ]);

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
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->query()
            ->where('name', 'mehdi');

        $this->request->shouldReceive('query')->andReturn([
            'name' => 'mehdi',
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi'], $users->getBindings());
    }

    public function testWhereZero()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->query()
            ->where('count_posts', 0);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => 0,
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['0'], $users->getBindings());
    }

    public function testWhereSomeParamNull()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->query()
            ->where('username', 'mehdi');

        $this->request->shouldReceive('query')->andReturn([
            'username' => 'mehdi',
            'family'   => null,
            'email'    => null,
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi'], $users->getBindings());
    }

    public function testRequestNull()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->query();

        $this->request->shouldReceive('query')->andReturn([]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testWhereSomeParamNull2()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->query()
            ->where('username', 'mehdi');

        $this->request->shouldReceive('query')->andReturn([
            'username'   => 'mehdi',
            'family'     => null,
            'created_at' => [
                'start' => null,
                'end'   => null,
            ],
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi'], $users->getBindings());
    }

    //todo implement wrapper method for out data

    //todo make serilize request
    public function testWhereIn()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->newQuery()
            ->wherein('username', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn([
            'username' => ['mehdi', 'ali'],
            'family'   => null,
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users->getBindings());
    }

    public function testWhereByOpt()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->newQuery()
            ->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => [
                'operator' => '>',
                'value'    => 35,
            ],
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([35], $users->getBindings());
    }

    public function testWhereByOptWithTrashed()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->newQuery()->withTrashed()
            ->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => [
                'operator' => '>',
                'value'    => 35,
            ],
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::withTrashed()->ignoreRequest(['id'])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([35], $users->getBindings());
    }

    public function testWhereByOptZero()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->newQuery()
            ->where('count_posts', '>', 0);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => [
                'operator' => '>',
                'value'    => 0,
            ],
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([0], $users->getBindings());
    }

//
    public function testWhereBetween()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereBetween(
            'created_at',
            [
                '2019-01-01 17:11:46',
                '2019-02-06 10:11:46',
            ]
        );

        $this->request->shouldReceive('query')->andReturn([
            'created_at' => [
                'start' => '2019-01-01 17:11:46',
                'end'   => '2019-02-06 10:11:46',
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $builder->getBindings());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $users->getBindings());
    }

    public function testSetWhiteList()
    {
        try {
            $this->request->shouldReceive('query')->andReturn([
                'role' => [
                    'admin', 'user',
                ],
            ]);

            EloquentBuilderTestModelParentStub::filter($this->request->query());
        } catch (EloquentFilterException $e) {
            $this->assertEquals(1, $e->getCode());
        }
    }

    public function testFParamOrder()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->newQuery()
            ->orderBy('id')
            ->orderBy('count_posts');

        $this->request->shouldReceive('query')->andReturn([
            'f_params' => [
                'orderBy' => [
                    'field' => 'id,count_posts',
                    'type'  => 'ASC',
                ],
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testFParamLimit()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->newQuery()
            ->limit(5);

        $this->request->shouldReceive('query')->andReturn([
            'f_params' => [
                'limit' => 5,
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testFParamException()
    {
        try {
            $this->request->shouldReceive('query')->andReturn([
                'f_params' => [
                    'orderBys' => [
                        'field' => 'id',
                        'type'  => 'ASC',
                    ],
                ],
            ]);

            EloquentBuilderTestModelParentStub::filter($this->request->query());
        } catch (EloquentFilterException $e) {
            $this->assertEquals(2, $e->getCode());
        }
    }

    public function testAddWhiteList()
    {
        $userModel2 = m::mock(User::class);
        $userModel2->shouldReceive('getWhiteListFilter')->andReturn([
            'id',
            'username',
            'family',
            'email',
            'count_posts',
            'created_at',
            'updated_at',
            'orders.name',
            'name',
        ]);

        $user_model = new User();
        $user_model->addWhiteListFilter('name');

        $this->assertEquals($user_model->getWhiteListFilter(), $userModel2->getWhiteListFilter());
    }

    public function testWhereHasRelationOneNestedModel()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereHas('foo', function ($q) {
            $q->where('bam', 'qux');
        })->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn([
            'foo' => [
                'bam' => 'qux',
            ],
            'baz' => 'joo',
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'joo'], $users->getBindings());
    }

    public function testWhereHasRelationTwoNested()
    {
        /// change request query string . to []
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereHas('foo.baz', function ($q) {
            $q->where('bam', 'qux');
        })->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn([
            'foo'         => [
                'baz' => [
                    'bam' => 'qux',
                ],
            ],
            'baz' => 'joo',
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'joo'], $users->getBindings());
    }

    public function testWhereHasRelationThereNested()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereHas('foo.baz', function ($q) {
            $q->where('bam', 'qux');
        })->whereHas('foo', function ($q) {
            $q->where('bam', 'boom');
        })->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn([
            'foo' => [
                'baz' => [
                    'bam' => 'qux',
                ],
                'bam' => 'boom',
            ],
            'baz' => 'joo',
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'boom', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'boom', 'joo'], $users->getBindings());
    }

    public function testWhereInSql()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereIn('baz', ['boom', 'joe', null]);

        $this->request->shouldReceive('query')->andReturn([
            'baz' => [
                'boom', 'joe', null,
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['boom', 'joe', null], $builder->getBindings());
        $this->assertEquals(['boom', 'joe', null], $users->getBindings());
    }

    public function testNullReqeust()
    {
        $this->__initQuery();

        $this->request->shouldReceive('query')->andReturn(null);

        $users = new User();
        $users = $users->scopeFilter(
            $this->builder,
            null
        );

        $this->assertEquals($users, $this->builder);
    }

    public function testNullArrReqeust()
    {
        $this->__initQuery();

        $this->request->shouldReceive('query')->andReturn([
        ]);

        $users = new User();
        $users = $users->scopeFilter(
            $this->builder,
            []
        );

        $this->assertEquals($users, $this->builder);
    }

    public function testWhereIgnoreParam()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz'          => 'joo',
                'google_index' => true,
                'is_payment'   => true,
            ]
        );

        $users = EloquentBuilderTestModelParentStub::ignoreRequest([
            'google_index',
            'is_payment',
        ])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());
    }

    public function testWhereIgnoreParamThatNotExistRequest()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz'          => 'joo',
                'google_index' => true,
            ]
        );

        $users = EloquentBuilderTestModelParentStub::ignoreRequest([
            'google_index',
            'is_payment_paypal',
        ])->filter($this->request->query());

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
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz'          => 'joo',
                'google_index' => true,
                'is_payment'   => true,
            ]
        );

        $users = EloquentBuilderTestModelParentStub::ignoreRequest([
            'google_index',
            'is_payment',
        ])->filter($this->request->query());

        $this->assertSame([
            'google_index',
            'is_payment',
        ], EloquentFilter::getIgnoredRequest());
    }

    public function testGetInjectedDetections()
    {
        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->whereHas('foo', function ($q) {
                $q->where('bam', 'like', '%mehdi%');
            })
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn([
            'baz' => [
                'value'               => 'boo',
                'limit'               => 10,
                'email'               => 'mehdifathi',
                'like_relation_value' => 'mehdi',
            ],
            'count_posts' => 10,
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::SetCustomDetection([WhereRelationLikeCondition::class])->filter();

        $this->assertEquals([WhereRelationLikeCondition::class], EloquentFilter::getInjectedDetections());
    }

    //todo update readme me by override custom detection in service provider laravel

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
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->query()
            ->where('baz', 'boo')
            ->where('count_posts', 22)
            ->orWhere('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn([
            'baz'         => 'boo',
            'count_posts' => 22,
            'or'          => [
                'baz' => 'joo',
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $users_to_sql = str_replace('(', '', $users->toSql());
        $users_to_sql = str_replace(')', '', $users_to_sql);
        $this->assertSame($users_to_sql, $builder->toSql());
        $this->assertEquals(['boo', 22, 'joo'], $users->getBindings());
    }

    public function testWhereByOpt1()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->where('count_posts', 345);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => 345,
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals([345], $builder->getBindings());
        $this->assertEquals([345], $users->getBindings());
    }

    public function testWhereIn2()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->query()->whereIn('username', ['mehdi22', 'ali22'])
            ->where('name', 'mehdi');

        $this->request->shouldReceive('query')->andReturn([
            'username' => ['mehdi22', 'ali22'],
            'name'     => 'mehdi',
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi22', 'ali22', 'mehdi'], $users->getBindings());
    }

    public function testWhereByOpt2()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->query()->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => [
                'operator' => '>',
                'value'    => 35,
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals([35], $users->getBindings());
    }

    public function testWhereHasWhereInRelationOneNestedModel()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereHas('foo', function ($q) {
            $q->whereIn('bam', ['qux', 'mehdi']);
        })->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn([
            'foo' => [
                'bam' => ['qux', 'mehdi'],
            ],
            'baz' => 'joo',
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'mehdi', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'mehdi', 'joo'], $users->getBindings());
    }

    public function testWhereHasInRelationThereNested()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereHas('foo.baz', function ($q) {
            $q->whereIn('bam', ['qux', 'mehdi']);
        })->whereHas('foo', function ($q) {
            $q->whereIn('bam', ['boom', 'noon']);
        })->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn([
            'foo' => [
                'baz' => [
                    'bam' => ['qux', 'mehdi'],
                ],
                'bam' => ['boom', 'noon'],
            ],
            'baz' => 'joo',
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'mehdi', 'boom', 'noon', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'mehdi', 'boom', 'noon', 'joo'], $users->getBindings());
    }

    public function testWhereBetweenWithEmailCountPosts()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereBetween(
            'created_at',
            [
                '2019-01-01 17:11:46',
                '2019-02-06 10:11:46',
            ]
        )->where('email', 'mehdifathi.developer@gmail.com')
            ->where('count_posts', '>', 35);

        $this->request->shouldReceive('query')->andReturn([
            'created_at' => [
                'start' => '2019-01-01 17:11:46',
                'end'   => '2019-02-06 10:11:46',
            ],
            'email'       => 'mehdifathi.developer@gmail.com',
            'count_posts' => [
                'operator' => '>',
                'value'    => 35,
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46', 'mehdifathi.developer@gmail.com', '35'], $builder->getBindings());
        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46', 'mehdifathi.developer@gmail.com', '35'], $users->getBindings());
    }

    public function testWhereBetweenWithZero()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->whereBetween(
            'count_posts',
            [
                0,
                200,
            ]
        )->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => [
                'start' => 0,
                'end'   => 200,
            ],
            'email'       => 'mehdifathi.developer@gmail.com',
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals([0, 200, 'mehdifathi.developer@gmail.com'], $builder->getBindings());
        $this->assertEquals([0, 200, 'mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testWhereLike1()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->query()->where('email', 'like', '%meh%');
        $this->request->shouldReceive('query')->andReturn([
            'email' => [
                'like' => '%meh%',
            ],
        ]);

        $users = EloquentBuilderTestModelParentStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertSame(['%meh%'], $builder->getBindings());
    }

    public function testSetDetection()
    {
        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->whereHas('foo', function ($q) {
                $q->where('bam', 'like', '%mehdi%');
            })
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn([
            'baz' => [
                'value'               => 'boo',
                'limit'               => 10,
                'email'               => 'mehdifathi',
                'like_relation_value' => 'mehdi',
            ],
            'count_posts' => 10,
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::SetCustomDetection([WhereRelationLikeCondition::class])->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['%mehdi%', 'boo', '%mehdifathi%', 10], $users->getBindings());
    }

    public function testEloquentFilterCustomDetection()
    {
        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->whereHas('foo', function ($q) {
                $q->where('bam', 'like', '%mehdi%');
            })
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn([
            'baz' => [
                'value'               => 'boo',
                'limit'               => 10,
                'email'               => 'mehdifathi',
                'like_relation_value' => 'mehdi',
            ],
            'count_posts' => 10,
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::filter();

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['%mehdi%', 'boo', '%mehdifathi%', 10], $users->getBindings());
    }

    //todo we can make a feature for override custom detection over default detection
    public function testSetDetection1()
    {
        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->where('count_posts', '=', 10)
            ->where('baz', '=', []);

        $this->request->shouldReceive('query')->andReturn([
            'count_posts' => 10,
            'baz'         => [
                'value'               => 'boo',
                'limit'               => 10,
                'email'               => 'mehdifathi',
                'like_relation_value' => 'mehdi',
            ],
        ]);

        //todo this method disable load detection default

        $users = EloquentBuilderTestModelNewStrategyStub::SetLoadDefaultDetection(false)->filter();

        $this->assertSame($users->toSql(), $builder->toSql());
    }

    public function testAcceptRequest()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz'          => 'joo',
                'google_index' => true,
                'gmail_api'    => 'dfsmfjkvx#$cew45',
            ]
        );

        $users = EloquentBuilderTestModelParentStub::AcceptRequest([
            'baz',
        ])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());

        $this->assertEquals(['baz' => 'joo'], EloquentFilter::getAcceptedRequest());
    }

    public function testAcceptRequest2()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $this->request->shouldReceive('query')->andReturn(
            [
                'google_index' => 'joo',
                'gmail_api'    => 'joo',
                'baz'          => 'joo',
            ]
        );

        $users = EloquentBuilderTestModelParentStub::AcceptRequest([
            'show_new_users',
        ])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals([], $builder->getBindings());
        $this->assertEquals([], $users->getBindings());

        $this->assertEquals(['show_new_users'], EloquentFilter::getAcceptedRequest());
    }

    public function testAcceptRequestNull()
    {
        $builder = new EloquentBuilderTestModelParentStub();

        $builder = $builder->where('baz', 'joo');

        $this->request->shouldReceive('query')->andReturn(
            [
                'baz' => 'joo',
            ]
        );

        $users = EloquentBuilderTestModelParentStub::AcceptRequest([])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['joo'], $builder->getBindings());
        $this->assertEquals(['joo'], $users->getBindings());

        $this->assertEquals(null, EloquentFilter::getAcceptedRequest());
    }

    public function testSerializeRequestFilter()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStub();

        $builder = $builder->newQuery()->wherein('username', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn([
            'new_username' => ['__mehdi__', '__ali__'],
            'family'       => null,
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users->getBindings());
    }

    public function testResponseFilter()
    {
        $builder = new EloquentBuilderTestModelCloseRelatedStubTwo();

        $builder = $builder->newQuery()->wherein('username', ['mehdi', 'ali']);

        $this->request->shouldReceive('query')->andReturn([
            'username' => ['mehdi', 'ali'],
            'family'   => null,
        ]);

        $users = EloquentBuilderTestModelCloseRelatedStubTwo::filter($this->request->query());

        $this->assertSame($users['data']->toSql(), $builder->toSql());

        $this->assertEquals(['mehdi', 'ali'], $users['data']->getBindings());
    }

    public function testRequestFilterKeyFilledConfig()
    {
        config(['eloquentFilter.request_filter_key' => 'filter']);

        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn([
            'filter' => [
                'email' => 'mehdifathi.developer@gmail.com',
            ],
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testFalseEnabledConfig()
    {
        config(['eloquentFilter.enabled' => false]);

        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn([
            'filter' => [
                'email' => 'mehdifathi.developer@gmail.com',
            ],
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::filter($this->request->query());

        $this->assertNotSame($users->toSql(), $builder->toSql());

        $this->assertNotEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testWhereBetweenWithRelation()
    {
        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()->
        select('eloquent_builder_test_model_new_strategy_stubs.name')->
        join('foo', 'foo.user_id', '=', 'eloquent_builder_test_model_new_strategy_stubs.id')
            ->whereBetween(
                'foo.created_at',
                [
                    '2019-01-01 17:11:46',
                    '2019-02-06 10:11:46',
                ]
            );

        $this->request->shouldReceive('query')->andReturn(['foo' => ['created_at' => ['start' => '2019-01-01 17:11:46', 'end' => '2019-02-06 10:11:46']]]); //delete it

        $users = EloquentBuilderTestModelNewStrategyStub::select('eloquent_builder_test_model_new_strategy_stubs.name')->
        join('foo', 'foo.user_id', '=', 'eloquent_builder_test_model_new_strategy_stubs.id')
            ->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['2019-01-01 17:11:46', '2019-02-06 10:11:46'], $users->getBindings());
    }

    public function testIgnoreRequestConfig()
    {
        config(['eloquentFilter.ignore_request' => ['show_query', 'new_trend']]);

        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn([
            'email'     => 'mehdifathi.developer@gmail.com',
            'show_query'=> true,
            'new_trend' => '2021',
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testIgnoreRequestBeforeSetConfig()
    {
        config(['eloquentFilter.ignore_request' => ['show_query', 'new_trend']]);

        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn([
            'email'     => 'mehdifathi.developer@gmail.com',
            'id'        => 99,
            'show_query'=> true,
            'new_trend' => '2021',
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::ignoreRequest(['id'])->filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testTrueEnabledConfig()
    {
        config(['eloquentFilter.enabled' => true]);

        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->where('email', 'mehdifathi.developer@gmail.com');

        $this->request->shouldReceive('query')->andReturn([
            'email' => 'mehdifathi.developer@gmail.com',
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::filter($this->request->query());

        $this->assertSame($users->toSql(), $builder->toSql());

        $this->assertEquals(['mehdifathi.developer@gmail.com'], $users->getBindings());
    }

    public function testFalseEnabledCustomDetectionConfig()
    {
        config(['eloquentFilter.enabled_custom_detection' => false]);

        $builder = new EloquentBuilderTestModelNewStrategyStub();

        $builder = $builder->query()
            ->whereHas('foo', function ($q) {
                $q->where('bam', 'like', '%mehdi%');
            })
            ->where('baz', '<>', 'boo')
            ->where('email', 'like', '%mehdifathi%')
            ->where('count_posts', '=', 10)
            ->limit(10);

        $this->request->shouldReceive('query')->andReturn([
            'baz' => [
                'value'               => 'boo',
                'limit'               => 10,
                'email'               => 'mehdifathi',
                'like_relation_value' => 'mehdi',
            ],
            'count_posts' => 10,
        ]);

        $users = EloquentBuilderTestModelNewStrategyStub::filter();

        $this->assertNotSame($users->toSql(), $builder->toSql());
        $this->assertNotEquals(['%mehdi%', 'boo', '%mehdifathi%', 10], $users->getBindings());
        $this->assertNull(EloquentFilter::getInjectedDetections());
    }

    public function tearDown(): void
    {
        m::close();
    }
}

class EloquentBuilderTestModelNewStrategyStub extends Model
{
    use Filterable;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'baz',
        'too',
        'count_posts',
        'foo.bam',
        'foo.created_at',
        'foo.baz.bam',
        'created_at',
        'email',
    ];

    public function foo()
    {
        return $this->belongsTo(EloquentBuilderTestModelCloseRelatedStub::class);
    }

    public function address()
    {
        return $this->belongsTo(EloquentBuilderTestModelCloseRelatedStub::class, 'foo_id');
    }

    public function activeFoo()
    {
        return $this->belongsTo(EloquentBuilderTestModelCloseRelatedStub::class, 'foo_id')->where('active', true);
    }

    public function EloquentFilterCustomDetection(): array
    {
        return [
            WhereRelationLikeCondition::class,
        ];
    }
}

class EloquentBuilderTestModelParentStub extends Model
{
    use Filterable;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'baz',
        'too',
        'count_posts',
        'foo.bam',
        'foo.baz.bam',
        'created_at',
        'email',
    ];

    public function foo()
    {
        return $this->belongsTo(EloquentBuilderTestModelCloseRelatedStub::class);
    }

    public function address()
    {
        return $this->belongsTo(EloquentBuilderTestModelCloseRelatedStub::class, 'foo_id');
    }

    public function activeFoo()
    {
        return $this->belongsTo(EloquentBuilderTestModelCloseRelatedStub::class, 'foo_id')->where('active', true);
    }
}

class EloquentBuilderTestModelCloseRelatedStub extends Model
{
    use Filterable;
    use SoftDeletes;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'username',
        'name',
        'count_posts',
    ];

    public function bar()
    {
        return $this->hasMany(\Illuminate\Tests\Database\EloquentBuilderTestModelFarRelatedStub::class);
    }

    public function baz()
    {
        return $this->hasMany(EloquentBuilderTestModelFarRelatedStub::class);
    }

    public function serializeRequestFilter($request)
    {
        if (!empty($request['new_username'])) {
            foreach ($request['new_username'] as &$item) {
                $item = trim($item, '__');
            }
            $request['username'] = $request['new_username'];
            unset($request['new_username']);
        }

        return $request;
    }
}

class EloquentBuilderTestModelCloseRelatedStubTwo extends Model
{
    use Filterable;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'username',
        'name',
        'count_posts',
    ];

    public function ResponseFilter($out)
    {
        $data['data'] = $out;

        return $data;
    }
}

class EloquentBuilderTestModelFarRelatedStub extends Model
{
    //
}

class EloquentBuilderTestModelSelfRelatedStub extends Model
{
    protected $table = 'self_related_stubs';

    public function parentFoo()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id', 'parent');
    }

    public function childFoo()
    {
        return $this->hasOne(self::class, 'parent_id', 'id');
    }

    public function childFoos()
    {
        return $this->hasMany(self::class, 'parent_id', 'id', 'children');
    }

    public function parentBars()
    {
        return $this->belongsToMany(self::class, 'self_pivot', 'child_id', 'parent_id', 'parent_bars');
    }

    public function childBars()
    {
        return $this->belongsToMany(self::class, 'self_pivot', 'parent_id', 'child_id', 'child_bars');
    }

    public function bazes()
    {
        return $this->hasMany(EloquentBuilderTestModelFarRelatedStub::class, 'foreign_key', 'id', 'bar');
    }
}

class EloquentBuilderTestStubWithoutTimestamp extends Model
{
    const UPDATED_AT = null;

    protected $table = 'table';
}

//todo enable/disable package in config file
// update readme file for set request_filter_key,enabled
// enable/disable custom detection
// update readme
