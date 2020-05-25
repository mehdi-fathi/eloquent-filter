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
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
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

        $userModel2 = m::mock(\Tests\Models\User::class);
//        $userModel2->shouldReceive('getWhiteListFilter')->andReturn([
//            'name',
//        ]);

        $userModel2->shouldReceive('setWhiteListFilter')->with(['name']);
        $userModel2->shouldReceive('getWhiteListFilter')->andReturn(['name']);
        $this->__initQuery($userModel2);

        $this->builder->shouldReceive('where')->with('name', 'mehdi');
        $this->builder->shouldReceive('where')->with('name', 'mehdi');
        $this->request->shouldReceive('all')->andReturn(['name' => 'mehdi']);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');

        $this->assertEquals($this->model, $this->builder);

    }


    public function tearDown(): void
    {
        m::close();
        unset($this->userModel);
    }
}
