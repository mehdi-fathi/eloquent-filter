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

    /**
     * @param $queryBuilderWrapper
     * @param $out
     *
     * @return mixed
     */
    public function responseFilterHandler($queryBuilderWrapper, $out)
    {
        if (method_exists($queryBuilderWrapper->getModel(), 'ResponseFilter')) {
            $out = $queryBuilderWrapper->responseFilter($out);
        }
        $this->setResponse($out);

    }
}
