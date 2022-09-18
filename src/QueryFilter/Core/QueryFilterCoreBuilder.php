<?php

namespace eloquentFilter\QueryFilter\Core;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryFilterCoreWrapper.
 */
class QueryFilterCoreBuilder
{

    /**
     * @var \eloquentFilter\QueryFilter\Core\EloquentQueryFilterCore
     */
    public $core;

    /**
     * @param array|null $request
     */
    public function __construct(QueryFilterCore $core)
    {
        $this->core = $core;
    }

    /**
     * @param Builder $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detect_injected
     *
     * @return void
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null)
    {

        $this->core->setBuilder($builder);

        if (!empty($request)) {
            $this->core->setRequest($request);
        }

        if (config('eloquentFilter.enabled') == false || empty($this->core->getRequest())) {
            return;
        }

        $this->core->handelSerializeRequestFilter();

        $this->core->makeAliasRequestFilter();

        $this->core->setFilterRequests($ignore_request, $accept_request, $this->core->getBuilder()->getModel());

        if (!empty($detect_injected)) {
            $this->core->setDetectInjected($detect_injected);
            $this->core->setDetectFactory($this->core->getDetectorFactory($this->core->getDefaultDetect(), $this->core->getDetectInjected()));
        }

        app()->bind('ResolverDetections', function () {
            return new ResolverDetections($this->core->getBuilder(), $this->core->getRequest(), $this->core->getDetectFactory());
        });

        $response = app('ResolverDetections')->getResolverOut();

        $response = $this->core->handelResponseFilter($response);

        return $response;
    }

    /**
     * @param null $index
     *
     * @return array|mixed|null
     */
    public function filterRequests($index = null)
    {
        if (!empty($index)) {
            return $this->core->getRequest()[$index];
        }

        return $this->core->getRequest();
    }

    /**
     * @return mixed
     */
    public function getAcceptedRequest()
    {
        return $this->core->getAcceptRequest();
    }

    /**
     * @return mixed
     */
    public function getIgnoredRequest()
    {
        return $this->core->getIgnoreRequest();
    }

    /**
     * @return mixed
     */
    public function getInjectedDetections()
    {
        return $this->core->getDetectInjected();
    }

}
