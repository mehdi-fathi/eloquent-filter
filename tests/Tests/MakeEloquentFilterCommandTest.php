<?php

namespace Tests\Tests;

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
     * @dataProvider modelClassProvider
     *
     * @param $argument
     * @param $class
     */
    public function testMakeClassName($argument, $class)
    {
        $this->command->shouldReceive('argument')->andReturn($argument);
        $this->command->makeClassName();
        $this->assertEquals("App\\ModelFilters\\$class", $this->command->getClassName());
    }

    /**
     * @return array
     */
    public function modelClassProvider()
    {
        return [
            ['User', 'UserFilter'],
        ];
    }
}
