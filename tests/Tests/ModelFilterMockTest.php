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

    public function setUp(): void
    {
        parent::setUp();
        $this->builder = m::mock(Builder::class);
        $this->builder->shouldReceive('getModel')->andReturn(new \Tests\Models\User());

        $this->request = m::mock(\Illuminate\Http\Request::class);
//        dd($this->requests);
    }

    public function testWhere()
    {

        $this->builder->shouldReceive('where')->with('username', 'mehdi');
        $this->builder->shouldReceive('where')->with('family', 'mehdi');
        $this->request->shouldReceive('all')->andReturn(['username' => 'mehdi','family'=>'mehdi']);

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


    public function tearDown(): void
    {
        m::close();
    }

}

class TestModelFilter
{
    public function relationSetup($query)
    {
        $query->where('setupcalled', '=', true);
    }

    public function filterItem($item)
    {
        $this->where($item);
    }

    public function uncallable($doThangs)
    {
        $this->orderBy($doThangs);
    }
}
