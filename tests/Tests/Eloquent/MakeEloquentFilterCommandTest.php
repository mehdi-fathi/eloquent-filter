<?php

namespace Tests\Tests\Eloquent;

use Illuminate\Filesystem\Filesystem;
use Mockery as m;

/**
 * Class MakeEloquentFilterCommandTest.
 */
class MakeEloquentFilterCommandTest extends \TestCase
{
    /**
     * @var m\LegacyMockInterface|m\MockInterface|Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var m\LegacyMockInterface|m\MockInterface|eloquentFilter\Command\MakeEloquentFilter[argument]
     */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();
        $this->filesystem = m::mock(Filesystem::class);
        $this->command = m::mock('eloquentFilter\Command\MakeEloquentFilter[argument]', [$this->filesystem]);
    }

    public function tearDown(): void
    {
        m::close();
    }

    /**
     *
     * @param $argument
     * @param $class
     */
    public function testMakeClassName($argument = 'User', $class = 'UserFilter')
    {
        $this->command->shouldReceive('argument')->andReturn($argument);
        $this->command->makeClassName();
        $this->assertEquals("App\\ModelFilters\\$class", $this->command->getClassName());
    }
}
