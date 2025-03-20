<?php

use Mockery as m;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class TestCase.
 */
class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @var m\LegacyMockInterface|m\MockInterface
     */
    public $request;

    /**
     * @var ParameterBag
     */
    protected $requestAttributes;

    public function setUp(): void
    {
        parent::setUp();

        // Create request mock
        $this->request = m::mock(Request::class);
        
        // Initialize ParameterBag for attributes
        $this->requestAttributes = new ParameterBag();

        // Mock User implementing Authenticatable
        $this->user = new class implements Authenticatable {
            public function getAuthIdentifier() {
                return 1;
            }

            public function getAuthIdentifierName() {
                return 'id';
            }

            public function getAuthPassword() {
                return 'hashed-password';
            }

            public function getRememberToken() {
                return 'remember-token';
            }

            public function setRememberToken($value) {
                // Not needed for our tests
            }

            public function getRememberTokenName() {
                return 'remember_token';
            }

            public function getAuthPasswordName()
            {
                return 'password';
            }
        };

        // Setup request methods
        $this->request->shouldReceive('user')->byDefault()->andReturn(null);
        $this->request->shouldReceive('path')->andReturn('/test');
        $this->request->shouldReceive('ip')->andReturn('127.0.0.1');
        
        // Setup attributes methods
        $this->request->shouldReceive('getAttribute')
            ->andReturnUsing(function ($key, $default = null) {
                return $this->requestAttributes->get($key, $default);
            });
            
        $this->request->shouldReceive('attributes')
            ->andReturn($this->requestAttributes);

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
