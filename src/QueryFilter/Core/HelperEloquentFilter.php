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
}
