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
}
