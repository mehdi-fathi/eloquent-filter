<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

/**
 *
 */
class ResponseFilter
{
    /**
     * @var
     */
    public $response;

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }
}
