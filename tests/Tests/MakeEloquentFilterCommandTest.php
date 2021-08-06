<?php

namespace Tests\Tests;

use Mockery as m;

class MakeEloquentFilterCommandTest extends \TestCase
{
    protected $filesystem;

    protected $command;

    public function setUp(): void
    {

        parent::setUp();
        $this->filesystem = m::mock(Illuminate\Filesystem\Filesystem::class);
        $this->command = m::mock('eloquentFilter\Command\MakeEloquentFilter[argument]', [$this->filesystem]);
    }

    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @dataProvider modelClassProvider
     */
    public function testMakeClassName($argument, $class)
    {
        $this->command->shouldReceive('argument')->andReturn($argument);
        $this->command->makeClassName();
        $this->assertEquals("App\\ModelFilters\\$class", $this->command->getClassName());
    }

    public function modelClassProvider()
    {
        return [
            ['User', 'UserFilter'],
//            ['Admin\\User', 'Admin\\UserFilter'],
//            ['UserFilter', 'UserFilter'],
//            ['user-filter', 'UserFilter'],
//            ['adminUser', 'AdminUserFilter'],
//            ['admin-user', 'AdminUserFilter'],
//            ['admin-user\\user-filter', 'AdminUser\\UserFilter'],
        ];
    }
}
