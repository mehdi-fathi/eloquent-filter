<?php

namespace Tests\Tests;

use eloquentFilter\Facade\EloquentFilter;
use EloquentFilter\ModelFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use eloquentFilter\QueryFilter\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;
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
        // todo refactor this

//        $this->__initQuery();
//        $this->builder->shouldReceive('where')->with('username', 'mehdi');
//        $this->builder->shouldReceive('where')->with('family', 'mehdi');
//        $this->request->shouldReceive('query')->andReturn(['username' => 'mehdi', 'family' => 'mehdi']);
//
//        $this->model = new QueryFilter($this->request->query());
//        $this->model = $this->model->apply($this->builder);
//        $this->assertEquals($this->model, $this->builder);

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

//
//    public function testWhereBetween()
//    {
//        $this->__initQuery();
//        $this->builder->shouldReceive('whereBetween')->with('created_at', [
//            '2019-01-01 17:11:46',
//            '2019-02-06 10:11:46',
//        ]);
//        $this->request->shouldReceive('query')->andReturn([
//            'created_at' => [
//                'start' => '2019-01-01 17:11:46',
//                'end'   => '2019-02-06 10:11:46',
//            ],
//        ]);
//
//        $ModelFilters = new QueryFilter($this->request->query());
//
//        $users = new User();
//        $users = $users->scopeFilter($this->builder, $this->request->query());
//
//        $this->assertEquals($users, $this->builder);
//    }

//    public function testPaginate()
//    {
//        //todo refactor this
    ////        $this->__initQuery();
    ////        $this->builder->shouldReceive('whereBetween')->with('created_at', [
    ////            '2019-01-01 17:11:46',
    ////            '2019-02-06 10:11:46',
    ////        ]);
    ////        $this->builder->shouldReceive('paginate')->with(5, ['*'], 'page', 1)->andReturn([]);
    ////
    ////        $this->request->shouldReceive('query')->andReturn([
    ////            'created_at' => [
    ////                'start' => '2019-01-01 17:11:46',
    ////                'end'   => '2019-02-06 10:11:46',
    ////            ],
    ////            'page' => 5,
    ////        ]);
    ////
    ////        $ModelFilters = new QueryFilter($this->request->query());
    ////        $this->model = $ModelFilters->apply($this->builder);
    ////
    ////        $paginate = $this->model->paginate(5, ['*'], 'page', 1);
    ////
    ////        $this->assertEquals($paginate, $this->builder->paginate(5, ['*'], 'page', 1));
    ////        $this->assertEquals($this->model, $this->builder);
//
//
//
//        $builder = new EloquentBuilderTestModelCloseRelatedStub();
//
//        $builder = $builder->newQuery()
//            ->where('count_posts', '>', 35)->paginate();
//
//        $this->request->shouldReceive('query')->andReturn([
//            'count_posts' => [
//                'operator' => '>',
//                'value'    => 35,
//            ],
//        ]);
//
//        $users = EloquentBuilderTestModelCloseRelatedStub::filter($this->request->query())->paginate();
//        dd($users);
//
//    }

    public function testSetWhiteList()
    {
        try {
            $this->request->shouldReceive('query')->andReturn([
                'role' => [
                    'admin', 'user',
                ],
            ]);

            EloquentBuilderTestModelParentStub::filter($this->request->query());
        } catch (\Exception $e) {
            $this->assertEquals(0, $e->getCode());
        }
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
        } catch (\Exception $e) {
            $this->assertEquals(0, $e->getCode());
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
            'foo.baz.bam' => 'qux',
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

    public function testWhereBetween1()
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

    public function tearDown(): void
    {
        m::close();
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
