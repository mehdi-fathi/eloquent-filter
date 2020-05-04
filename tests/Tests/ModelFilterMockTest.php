<?php


namespace Tests\Tests;

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use eloquentFilter\QueryFilter\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use EloquentFilter\ModelFilter;
use Tests\Controllers\UsersController;
use Tests\Models\User;

class ModelFilterMockTest extends \TestCase
{
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
        $this->userModel = m::mock(User::class);
        $this->builder->shouldReceive('getModel')->andReturn(new \Tests\Models\User());
        $this->request = m::mock(\Illuminate\Http\Request::class);
    }

    public function testWhere()
    {

        $this->builder->shouldReceive('where')->with('username', 'mehdi');
        $this->builder->shouldReceive('where')->with('family', 'mehdi');
        $this->request->shouldReceive('all')->andReturn(['username' => 'mehdi', 'family' => 'mehdi']);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }


    public function testWhereIn()
    {

        $this->builder->shouldReceive('whereIn')->with('username', ['mehdi', 'ali']);
        $this->request->shouldReceive('all')->andReturn(['username' => ['mehdi', 'ali']]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }

    public function testWhereByOpt()
    {

        $this->builder->shouldReceive('where')->with('count_posts', '>', 35);
        $this->request->shouldReceive('all')->andReturn([
            'count_posts' => [
                'operator' => '>',
                'value' => 35,
            ]
        ]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }

    public function testWhereBetween()
    {

        $this->builder->shouldReceive('whereBetween')->with('created_at', [
            '2019-01-01 17:11:46',
            '2019-02-06 10:11:46',
        ]);
        $this->request->shouldReceive('all')->andReturn([
            'created_at' => [
                'start' => '2019-01-01 17:11:46',
                'end' => '2019-02-06 10:11:46',
            ]
        ]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');
        $this->assertEquals($this->model, $this->builder);
    }

    public function testPaginate()
    {

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
            'page' => 5
        ]);

        $this->model = new ModelFilters($this->request);
        $this->model = $this->model->apply($this->builder, 'users');

        $paginate = $this->model->paginate(5, ['*'], 'page', 1);

        $this->assertEquals($paginate, $this->builder->paginate(5, ['*'], 'page', 1));
        $this->assertEquals($this->model, $this->builder);
    }

    public function tearDown(): void
    {
        m::close();
    }
}
