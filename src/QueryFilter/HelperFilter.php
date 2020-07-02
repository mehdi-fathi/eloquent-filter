<?php

namespace eloquentFilter\QueryFilter;

use Illuminate\Support\Arr;

/**
 * Trait HelperFilter.
 */
trait HelperFilter
{
    /**
     * @param array $arr
     *
     * @return bool
     */
    private function isAssoc(array $arr)
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param       $field
     * @param array $args
     *
     * @return array|null
     */
    private function convertRelationArrayRequestToStr($field, array $args)
    {
        $out = Arr::dot($args, $field . '.');
        return $out;
    }

    /**
     * @param array|null $request
     */
    protected function setRequest($request): void
    {
        if (!empty($request['page'])) {
            unset($request['page']);
        }
        $this->request = $request;
    }

    /**
     * @return array|null
     */
    protected function getRequest(): ?array
    {
        return $this->request;
    }

    /**
     * @param array|null $ignore_request
     *
     * @param            $builder_model
     *
     * @return array|null
     */
    protected function setFilterRequests(array $ignore_request = null, $builder_model): ?array
    {
        if (!empty($ignore_request) && !empty($this->getRequest())) {
            $this->setIgnoreReqeust($ignore_request);
        }
        if (!empty($this->getRequest())) {
            foreach ($this->getRequest() as $name => $value) {
                if (is_array($value) && method_exists($builder_model, $name)) {
                    if ($this->isAssoc($value)) {
                        unset($this->request[$name]);
                        $out = $this->convertRelationArrayRequestToStr($name, $value);
                        $this->setRequest(array_merge($out, $this->request));
                    }
                }
            }
        }

        return $this->getRequest();
    }

    /**
     * @param array $ignore_request
     */
    private function setIgnoreReqeust(array $ignore_request): void
    {
        $data = Arr::except($this->getRequest(), $ignore_request);
        $this->setRequest($data);
    }

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
}
