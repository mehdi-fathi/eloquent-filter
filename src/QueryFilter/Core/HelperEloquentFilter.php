<?php

namespace eloquentFilter\QueryFilter\Core;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Trait HelperEloquentFilter.
 */
trait HelperEloquentFilter
{
    /**
     * @param null $index
     *
     * @return array|mixed|null
     */
    public function filterRequests($index = null)
    {
        if (!empty($index)) {
            return $this->requestFilter->getRequest()[$index];
        }

        return $this->requestFilter->getRequest();
    }

    /**
     * @return mixed
     */
    public function getAcceptedRequest()
    {
        return $this->requestFilter->getAcceptRequest();
    }

    /**
     * @return mixed
     */
    public function getIgnoredRequest()
    {
        return $this->requestFilter->getIgnoreRequest();
    }


    /**
     * @return mixed
     */
    public function getRequestEncoded()
    {
        return $this->requestFilter->requestEncoded;
    }

    /**
     * @return mixed
     */
    public function setRequestEncoded($request, $salt)
    {
        return $this->requestFilter->setRequestEncoded($request, $salt);
    }

    /**
     * @return mixed
     */
    public function getInjectedDetections()
    {
        return $this->queryFilterCore->getInjectedDetections();
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->responseFilter->getResponse();
    }

    /**
     * @param EloquentBuilder|QueryBuilder $builder
     *
     * @return array<string, mixed>
     */
    public function explain(EloquentBuilder|QueryBuilder $builder): array
    {
        $used = config('eloquentFilter.enabled') && !empty($this->requestFilter->getRequest());

        if (!config('eloquentFilter.explain.enabled', true)) {
            return [
                'enabled' => false,
                'used' => $used,
            ];
        }

        $snapshot = $this->responseFilter->getExplainSnapshot()->toArray();

        $model = null;
        $table = null;

        if ($builder instanceof EloquentBuilder) {
            $model = get_class($builder->getModel());
            $table = $builder->getModel()->getTable();
        } elseif ($builder instanceof QueryBuilder) {
            $table = $builder->from;
        }

        return [
            'enabled' => true,
            'used' => $used,
            'driver' => $this->getNameBuilder(),
            'model' => $model,
            'table' => $table,
            'request' => [
                'original' => $this->requestFilter->getOriginalRequest(),
                'processed' => $this->requestFilter->getRequest(),
                'ignored' => $this->requestFilter->getIgnoreRequest(),
                'accepted' => $this->requestFilter->getAcceptRequest(),
            ],
            'detections' => [
                'injected' => property_exists($this, 'detectionsInjected') ? $this->detectionsInjected : null,
                'blacklisted' => property_exists($this, 'blackListDetections') ? $this->blackListDetections : null,
            ],
            'applied' => $snapshot['applied'],
            'skipped' => $snapshot['skipped'],
            'query' => [
                'sql' => $builder->toSql(),
                'bindings' => $builder->getBindings(),
            ],
        ];
    }
}
