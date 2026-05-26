<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

/**
 * Wraps a query builder so filter()->explain() does not conflict with
 * Laravel's native Query Builder explain() (SQL EXPLAIN).
 */
class FilterBuilderProxy
{
    public function __construct(private readonly mixed $builder)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function explain(): array
    {
        return app('eloquentFilter')->explain($this->builder);
    }

    public function __call(string $method, array $parameters): mixed
    {
        $result = $this->builder->{$method}(...$parameters);

        if ($result === $this->builder) {
            return $this;
        }

        return $result;
    }

    public function __get(string $name): mixed
    {
        return $this->builder->{$name};
    }

    public function getWrappedBuilder(): mixed
    {
        return $this->builder;
    }
}
