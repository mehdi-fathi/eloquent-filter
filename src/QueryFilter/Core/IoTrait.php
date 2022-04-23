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

    private function __makeAliasRequestFilter()
    {
        if (empty($this->getRequest())) {
            return;
        }
        if ($alias_list_filter = $this->builder->getModel()->getAliasListFilter()) {
            $req = $this->getRequest();

            $req = collect($req)->mapWithKeys(function ($item, $key) use ($alias_list_filter) {

                $key1 = array_search($key, $alias_list_filter);

                if (!empty($alias_list_filter[$key1])) {

                    $req[$key1] = $this->getRequest()[$key];

                } else {
                    $req[$key] = $item;
                }
                return $req;

            })->toArray();

            if (!empty($req)) {
                $this->setRequest($req);
            }
        }
    }
}
