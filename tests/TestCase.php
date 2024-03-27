<?php

use Mockery as m;

/**
 * Class TestCase.
 */
class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @var m\LegacyMockInterface|m\MockInterface
     */
    public $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = m::mock(\Illuminate\Http\Request::class);

        app()->bind(
            'request',
            function () {
                return $this->request;
            }
        );
    }


    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [eloquentFilter\ServiceProvider::class];
    }
}
