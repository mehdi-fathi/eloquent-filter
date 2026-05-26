<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\IO;

/**
 *
 */
class ResponseFilter
{
    /**
     * @var
     */
    public mixed $response;

    private FilterExplainSnapshot $explainSnapshot;

    public function __construct()
    {
        $this->explainSnapshot = new FilterExplainSnapshot();
    }

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

    public function getExplainSnapshot(): FilterExplainSnapshot
    {
        return $this->explainSnapshot;
    }

    public function resetExplainSnapshot(): void
    {
        $this->explainSnapshot->reset();
    }
}
