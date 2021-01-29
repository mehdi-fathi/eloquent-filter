<?php

namespace eloquentFilter\QueryFilter;

/**
 * Trait HelperFilter.
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
            return $this->getRequest()[$index];
        }

        return $this->getRequest();
    }

    /**
     * @return mixed
     */
    public function getAcceptedRequest()
    {
        return $this->getAcceptRequest();
    }

    /**
     * @return mixed
     */
    public function getIgnoredRequest()
    {
        return $this->getIgnoreRequest();
    }

    /**
     * @return mixed
     */
    public function getInjectedDetections()
    {
        return $this->getDetectInjected();
    }
}
