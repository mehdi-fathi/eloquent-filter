<?php

namespace eloquentFilter\QueryFilter\Core;

trait IoTrait
{
    /**
     * @param $out
     *
     * @return mixed
     */
    private function __handelResponseFilter($out)
    {
        if (method_exists($this->builder->getModel(), 'ResponseFilter')) {
            return $this->builder->getModel()->ResponseFilter($out);
        }

        return $out;
    }

    private function __handelSerializeRequestFilter()
    {
        if (method_exists($this->builder->getModel(), 'serializeRequestFilter')) {
            if (!empty($this->getRequest())) {
                $serializeRequestFilter = $this->builder->getModel()->serializeRequestFilter($this->getRequest());
                $this->setRequest($serializeRequestFilter);
            }
        }
    }
}
