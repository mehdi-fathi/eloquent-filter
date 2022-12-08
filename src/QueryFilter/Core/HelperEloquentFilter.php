<?php

namespace eloquentFilter\QueryFilter\Core;

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
            return $this->request->getRequest()[$index];
        }

        return $this->request->getRequest();
    }

    /**
     * @return mixed
     */
    public function getAcceptedRequest()
    {
        return $this->request->getAcceptRequest();
    }

    /**
     * @return mixed
     */
    public function getIgnoredRequest()
    {
        return $this->request->getIgnoreRequest();
    }

    /**
     * @return mixed
     */
    public function getInjectedDetections()
    {
        return $this->core->getInjectedDetections();
    }
}
