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
    public mixed $response;

    /**
     * @return mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse(mixed $response): void
    {
        $this->response = $response;
    }
}
