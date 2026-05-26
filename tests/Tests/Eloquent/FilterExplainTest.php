<?php

namespace Tests\Tests\Eloquent;

use eloquentFilter\Facade\EloquentFilter;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\MainBuilderQueryByCondition;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\Models\Category;
use Tests\Models\CustomDetect\WhereRelationLikeCondition;
use Tests\Models\User;

class FilterExplainTest extends \TestCase
{
    public function testExplainReturnsAppliedWhereCondition(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'title' => 'sport',
        ]);

        $categories = Category::filter($this->request->query());
        $explain = $categories->explain();

        $this->assertTrue($explain['enabled']);
        $this->assertTrue($explain['used']);
        $this->assertSame(MainBuilderQueryByCondition::NAME, $explain['driver']);
        $this->assertSame(Category::class, $explain['model']);
        $this->assertSame('categories', $explain['table']);
        $this->assertSame('sport', $explain['request']['processed']['title']);
        $this->assertCount(1, $explain['applied']);
        $this->assertSame('title', $explain['applied'][0]['field']);
        $this->assertSame('Where', $explain['applied'][0]['condition']);
        $this->assertSame('sport', $explain['applied'][0]['values']);
        $this->assertSame($categories->toSql(), $explain['query']['sql']);
        $this->assertSame($categories->getBindings(), $explain['query']['bindings']);
    }

    public function testExplainReturnsWhereLikeCondition(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'title' => ['like' => '%sport%'],
        ]);

        $categories = Category::filter($this->request->query());
        $explain = $categories->explain();

        $this->assertSame('WhereLike', $explain['applied'][0]['condition']);
        $this->assertSame('title', $explain['applied'][0]['field']);
    }

    public function testExplainIncludesOriginalAndProcessedRequest(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'new_title' => ['__sport__'],
        ]);

        $categories = Category::filter($this->request->query());
        $explain = $categories->explain();

        $this->assertSame(['new_title' => ['__sport__']], $explain['request']['original']);
        $this->assertSame(['title' => ['sport']], $explain['request']['processed']);
    }

    public function testExplainIncludesIgnoredRequestFields(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'title' => 'sport',
            'ignored_field' => 'value',
        ]);

        $categories = Category::ignoreRequest(['ignored_field'])->filter($this->request->query());
        $explain = $categories->explain();

        $this->assertContains('ignored_field', $explain['request']['ignored']);
        $this->assertArrayNotHasKey('ignored_field', $explain['request']['processed']);
    }

    public function testExplainIncludesInjectedDetections(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'baz' => [
                'value' => 'boo',
                'limit' => 10,
                'email' => 'mehdifathi',
                'like_relation_value' => 'mehdi',
            ],
            'count_posts' => 10,
        ]);

        $users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();
        $explain = $users->explain();

        $this->assertSame([WhereRelationLikeCondition::class], $explain['detections']['injected']);
    }

    public function testExplainViaFacade(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'title' => 'sport',
        ]);

        $categories = Category::filter($this->request->query());
        $explain = EloquentFilter::explain($categories);

        $this->assertTrue($explain['enabled']);
        $this->assertSame('title', $explain['applied'][0]['field']);
    }

    public function testExplainDisabledByConfig(): void
    {
        Config::set('eloquentFilter.explain.enabled', false);

        $this->request->shouldReceive('query')->andReturn([
            'title' => 'sport',
        ]);

        $categories = Category::filter($this->request->query());
        $explain = $categories->explain();

        $this->assertFalse($explain['enabled']);
        $this->assertTrue($explain['used']);
        $this->assertArrayNotHasKey('query', $explain);

        Config::set('eloquentFilter.explain.enabled', true);
    }

    public function testExplainForDbBuilder(): void
    {
        $this->request->shouldReceive('query')->andReturn([
            'title' => 'sport',
        ]);

        $categories = DB::table('categories')->filter();
        $explain = $categories->explain();

        $this->assertSame(DBBuilderQueryByCondition::NAME, $explain['driver']);
        $this->assertNull($explain['model']);
        $this->assertSame('categories', $explain['table']);
        $this->assertSame('Where', $explain['applied'][0]['condition']);
        $this->assertSame($categories->toSql(), $explain['query']['sql']);
    }
}
