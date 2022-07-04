<?php

namespace eloquentFilter\QueryFilter\Core;

use eloquentFilter\QueryFilter\HelperEloquentFilter;
use eloquentFilter\QueryFilter\HelperFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QueryFilterWrapper.
 */
class QueryFilterWrapper extends QueryFilter
{
    use IoTrait;
    use HelperFilter;
    use HelperEloquentFilter;

    /**
     * @param Builder $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array $detect_injected
     *
     * @return void
     */
    public function apply(Builder $builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detect_injected = null)
    {
        $this->setBuilder($builder);

        if (!empty($request)) {
            $this->setRequest($request);
        }

        if (config('eloquentFilter.enabled') == false || empty($this->getRequest())) {
            return;
        }

        $this->__handelSerializeRequestFilter();

        $this->__makeAliasRequestFilter();

        $this->setFilterRequests($ignore_request, $accept_request, $this->getBuilder()->getModel());

        if (!empty($detect_injected)) {
            $this->setDetectInjected($detect_injected);
            $this->setDetectFactory($this->getDetectorFactory($this->getDefaultDetect(), $this->getInjectedDetections()));
        }

        $ResolverDetections = new ResolverDetections($this->getBuilder(), $this->getRequest(), $this->getDetectFactory());
        $response = $ResolverDetections->getResolverOut();

        $response = $this->__handelResponseFilter($response);

        return $response;
    }
}
