<?php

namespace eloquentFilter\QueryFilter\Core;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\ParameterBag;

trait RateLimiting
{
    /**
     * Check if the request should be rate limited
     *
     * @throws ThrottleRequestsException
     */
    protected function checkRateLimit(): void
    {
        if (!config('eloquentFilter.rate_limit.enabled')) {
            return;
        }


        /** @var RateLimiter $limiter */
        $limiter = App::make('eloquent.filter.limiter');
        
        $key = $this->resolveRateLimitKey();
        $maxAttempts = config('eloquentFilter.rate_limit.max_attempts', 60);
        $decayMinutes = config('eloquentFilter.rate_limit.decay_minutes', 1);

        if ($limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $limiter->availableIn($key);
            
            $headers = [
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => $seconds,
                'Retry-After' => $seconds,
            ];

            throw new ThrottleRequestsException(
                config('eloquentFilter.rate_limit.error_message', 'Too many filter requests. Please try again later.'),
                null,
                $headers
            );
        }

        $limiter->hit($key, $decayMinutes * 60);

        // Store rate limit info in request for later use
        if (config('eloquentFilter.rate_limit.include_headers', true)) {
            $remaining = $limiter->remaining($key, $maxAttempts);
            $request = request();
            
            // Ensure attributes is initialized
            if (!isset($request->attributes)) {
                $request->attributes = new ParameterBag();
            }

            
            $request->attributes->set('rate_limit', [
                'limit' => $maxAttempts,
                'remaining' => $remaining
            ]);
        }
    }

    /**
     * Get the rate limiting key
     */
    protected function resolveRateLimitKey(): string
    {
        $request = request();
        
        return sha1(sprintf(
            '%s|%s|%s',
            $request->user() ? $request->user()->getAuthIdentifier() : $request->ip(),
            $request->path(),
            get_class($this)
        ));
    }
} 