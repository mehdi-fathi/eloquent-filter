<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseClause.
 */
abstract class BaseClause
{
    /**
     * BaseClause constructor.
     *
     * @param $values
     * @param $filter
     */
    public function __construct(protected $values, protected $filter)
    {
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $nextFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function handle($query, $nextFilter)
    {
        $query = $nextFilter($query);

        $startTime = microtime(true);

        $out = $this->apply($query);

        $this->recordLog($out, $startTime);

        return $out;
    }

    /**
     * @param $query
     *
     * @return Builder
     */
    abstract protected function apply($query);

    /**
     * @param $query
     * @param $startTime
     * @return void
     */
    public function recordLog($query, $startTime): void
    {
        if (config('eloquentFilter.log.has_keeping_query')) {

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            if (!empty(config('eloquentFilter.log.max_time_query'))) {

                if ($executionTime >= config('eloquentFilter.log.max_time_query')) {

                    $this->createLog($query, $executionTime);
                }

            } else {

                $this->createLog($query, $executionTime);
            }

        }

    }

    /**
     * @param $query
     * @param $executionTime
     * @return void
     */
    private function createLog($query, $executionTime): void
    {
        Log::info('eloquentFilter query', [
            'query' => $query->toSql(),
            'binding' => $query->getBindings(),
            'time' => $executionTime,
            'type' => config('eloquentFilter.log.type'),
        ]);
    }
}
