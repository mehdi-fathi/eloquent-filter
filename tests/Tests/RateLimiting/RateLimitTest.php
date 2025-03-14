<?php

namespace Tests\RateLimiting;

use eloquentFilter\QueryFilter\Core\RateLimiting;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Mockery as m;

class RateLimitTest extends \TestCase
{
    use RateLimiting;

    /**
     * @var m\LegacyMockInterface|m\MockInterface
     */
    protected $rateLimiter;

    public function setUp(): void
    {
        parent::setUp();

        $this->rateLimiter = m::mock(RateLimiter::class);
        app()->instance('eloquent.filter.limiter', $this->rateLimiter);
        config(['eloquentFilter.rate_limit.enabled' => true]);
    }

    protected function mockRateLimiterNotExceeded()
    {
        $this->rateLimiter->shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(false);
            
        $this->rateLimiter->shouldReceive('hit')
            ->once();
            
        $this->rateLimiter->shouldReceive('remaining')
            ->once()
            ->andReturn(59);
    }

    protected function mockRateLimiterExceeded()
    {
        $this->rateLimiter->shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(true);
            
        $this->rateLimiter->shouldReceive('availableIn')
            ->once()
            ->andReturn(60);
    }

    public function testRateLimitNotExceeded()
    {
        $this->mockRateLimiterNotExceeded();
        
        // This should not throw an exception
        $this->checkRateLimit();

        // Verify rate limit info was stored
        $rateLimitInfo = request()->attributes->all()['rate_limit'];

        $this->assertEquals(60, $rateLimitInfo['limit']);
        $this->assertEquals(59, $rateLimitInfo['remaining']);
    }

    public function testRateLimitExceeded()
    {
        $this->mockRateLimiterExceeded();
        
        $this->expectException(ThrottleRequestsException::class);
        $this->checkRateLimit();
    }

    public function testRateLimitDisabled()
    {
        config(['eloquentFilter.rate_limit.enabled' => false]);

        // No methods should be called on the rate limiter
        $this->rateLimiter->shouldNotReceive('tooManyAttempts');
        $this->rateLimiter->shouldNotReceive('hit');
        $this->rateLimiter->shouldNotReceive('remaining');

        // This should not throw an exception
        $this->checkRateLimit();
    }

    public function testRateLimitWithAuthenticatedUser()
    {
        $this->actingAs($this->user);
        $this->mockRateLimiterNotExceeded();
        
        $this->checkRateLimit();

        // Verify rate limit info was stored
        $rateLimitInfo = request()->attributes->all()['rate_limit'];


        $this->assertEquals(60, $rateLimitInfo['limit']);
        $this->assertEquals(59, $rateLimitInfo['remaining']);
    }

    public function testRateLimitWithCustomUser()
    {
        $customUser = new class implements Authenticatable {
            public function getAuthIdentifier() {
                return 999;
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

        $this->actingAs($customUser);
        $this->mockRateLimiterNotExceeded();
        
        $this->checkRateLimit();

        // Verify rate limit info was stored
        $rateLimitInfo = request()->attributes->all()['rate_limit'];
        $this->assertEquals(60, $rateLimitInfo['limit']);
        $this->assertEquals(59, $rateLimitInfo['remaining']);
    }
} 