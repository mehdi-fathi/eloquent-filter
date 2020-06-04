<?php

namespace eloquentFilter\QueryFilter;

use eloquentFilter\QueryFilter\Queries\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class QueryFilter.
 *
 * @property \eloquentFilter\QueryFilter\Queries\QueryBuilder queryBuilder
 */
class QueryFilter
{
    use HelperFilter;
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var
     */
    protected $builder;
    /**
     * @var
     */
    protected $queryBuilder;

    /**
     * QueryFilter constructor.
     *
     * @param array $request
     */
    public function __construct(?array $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array|null                            $reqeust
     * @param array|null                            $ignore_request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, array $reqeust = null, array $ignore_request = null): Builder
    {
        $this->builder = $builder;
        $this->queryBuilder = new QueryBuilder($builder);

        if (!empty($reqeust)) {
            $this->request = $reqeust;
        }

        $requests = $this->filters($ignore_request);

        if (empty($requests)) {
            return $this->builder;
        }
        foreach ($requests as $name => $value) {

            if (is_array($value) && method_exists($this->builder->getModel(), $name)) {
                if ($this->isAssoc($value)) {
                    unset($requests[$name]);
                    $out = $this->convertRelationArrayRequestToStr($name, $value);
                    $requests = array_merge($out, $requests);
                }
            }
        }

        foreach ($requests as $name => $value) {
            call_user_func([$this, $name], $value);
            // It resolve methods in filters class in child
        }

        return $this->builder;
    }


    /**
     * @param array|null $ignore_request
     *
     * @return array|null
     */
    public function filters(array $ignore_request = null): ?array
    {
        if (!empty($ignore_request)) {
            $this->request = Arr::except($this->request, $ignore_request);
        }
        return $this->request;
    }
}
