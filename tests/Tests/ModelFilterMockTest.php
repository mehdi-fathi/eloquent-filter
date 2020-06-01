<?php

namespace Tests\Tests;

use EloquentFilter\ModelFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
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

    protected function makeRequest()
    {
        $this->request = m::mock(\Illuminate\Http\Request::class);
    }

    protected function __initQuery($obj = null)
    {
        $this->makeBuilderWithModel($obj);
        $this->makeRequest();
    }

    public function testWhere()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('where')->with('username', 'mehdi');
        $this->builder->shouldReceive('where')->with('family', 'mehdi');
        $this->request->shouldReceive('all')->andReturn(['username' => 'mehdi', 'family' => 'mehdi']);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }

    public function testWhereIn()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('whereIn')->with('username', ['mehdi', 'ali']);
        $this->request->shouldReceive('all')->andReturn(['username' => ['mehdi', 'ali']]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }

    public function testWhereByOpt()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('where')->with('count_posts', '>', 35);
        $this->request->shouldReceive('all')->andReturn([
            'count_posts' => [
                'operator' => '>',
                'value' => 35,
            ],
        ]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }

    public function testWhereBetween()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('whereBetween')->with('created_at', [
            '2019-01-01 17:11:46',
            '2019-02-06 10:11:46',
        ]);
        $this->request->shouldReceive('all')->andReturn([
            'created_at' => [
                'start' => '2019-01-01 17:11:46',
                'end' => '2019-02-06 10:11:46',
            ],
        ]);

        $this->model = new ModelFilters($this->request);

        $users = new User();
        $users = $users->scopeFilter($this->builder, $this->model);

        $this->assertEquals($users, $this->builder);
    }

    public function testPaginate()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('whereBetween')->with('created_at', [
            '2019-01-01 17:11:46',
            '2019-02-06 10:11:46',
        ]);
        $this->builder->shouldReceive('paginate')->with(5, ['*'], 'page', 1)->andReturn([]);

        $this->request->shouldReceive('all')->andReturn([
            'created_at' => [
                'start' => '2019-01-01 17:11:46',
                'end' => '2019-02-06 10:11:46',
            ],
            'page' => 5,
        ]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');

        $paginate = $this->model->paginate(5, ['*'], 'page', 1);

        $this->assertEquals($paginate, $this->builder->paginate(5, ['*'], 'page', 1));
        $this->assertEquals($this->model, $this->builder);
    }

    public function testSetWhiteList()
    {
        $userModel2 = m::mock(User::class);
        $userModel2->shouldReceive('getWhiteListFilter')->andReturn(['name']);
        $this->__initQuery($userModel2);

        $this->builder->shouldReceive('where')->with('name', 'mehdi');
        $this->builder->shouldReceive('where')->with('name', 'mehdi');
        $this->request->shouldReceive('all')->andReturn(['name' => 'mehdi']);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');

        $this->assertEquals($this->model, $this->builder);
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

//        dd($user_model->getWhiteListFilter(),$userModel2->getWhiteListFilter());

        $this->assertEquals($user_model->getWhiteListFilter(), $userModel2->getWhiteListFilter());
    }

    public function testWhereLike()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('where')->with('username', 'like', '%meh%');
        $this->request->shouldReceive('all')->andReturn([
            'username' => [
                'like' => '%meh%',
            ],
        ]);

        $this->model = new ModelFilters($this->request);

        $users = new User();
        $users = $users->scopeFilter($this->builder, $this->model);

        $this->assertEquals($users, $this->builder);
    }

    public function testWhereRelation1()
    {
        $builder = new EloquentBuilderTestModelParentStub;

        $builder = $builder->whereHas('foo', function ($q) {
            $q->where('bam', 'qux');
        })->where('baz', 'joo');

        $this->makeRequest();

        $this->request->shouldReceive('all')->andReturn([
            'foo' => [
                'bam' => 'qux'
            ],
            'baz' => 'joo',
        ]);

        $filters = new ModelFilters($this->request);

        $users = EloquentBuilderTestModelParentStub::filter($filters);

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'joo'], $users->getBindings());
    }

    public function testWhereRelation2()
    {
        /// change request query string . to []
        $builder = new EloquentBuilderTestModelParentStub;

        $builder = $builder->whereHas('foo.baz', function ($q) {
            $q->where('bam', 'qux');
        })->where('baz', 'joo');

        $this->makeRequest();

        $this->request->shouldReceive('all')->andReturn([
            'foo.baz.bam' => 'qux',
            'foo' => [
                'baz' => [
                    'bam' => 'qux'
                ]
            ],
            'baz' => 'joo',
        ]);

        $filters = new ModelFilters($this->request);

        $users = EloquentBuilderTestModelParentStub::filter($filters);

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'joo'], $users->getBindings());
    }


    public function testWhereRelation3()
    {
        $builder = new EloquentBuilderTestModelParentStub;

        $builder = $builder->whereHas('foo.baz', function ($q) {
            $q->where('bam', 'qux');
        })->whereHas('foo', function ($q) {
            $q->where('bam', 'boom');
        })->where('baz', 'joo');

        $this->makeRequest();

        $this->request->shouldReceive('all')->andReturn([
            'foo' => [
                'baz' => [
                    'bam' => 'qux'
                ],
                'bam' => 'boom'
            ],
            'baz' => 'joo',
        ]);

        $filters = new ModelFilters($this->request);

        $users = EloquentBuilderTestModelParentStub::filter($filters);

        $this->assertSame($users->toSql(), $builder->toSql());
        $this->assertEquals(['qux', 'boom', 'joo'], $builder->getBindings());
        $this->assertEquals(['qux', 'boom', 'joo'], $users->getBindings());
    }


    public function testWhereLike2()
    {
        $this->__initQuery();
        $this->builder->shouldReceive('where')->once()->with('username', 'like', '%ahm%');
        $this->builder->shouldReceive('where')->once()->with('family', 'mehdi');

        $this->request->shouldReceive('all')->andReturn([
            'username' => [
                'like' => '%ahm%',
            ],
            'family' => 'mehdi',
        ]);

        $this->model = new ModelFilters($this->request);

        $users = new User();
        $users = $users->scopeFilter($this->builder, $this->model);

        $this->assertEquals($users, $this->builder);
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
        'foo.bam',
        'foo.baz.bam',
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
