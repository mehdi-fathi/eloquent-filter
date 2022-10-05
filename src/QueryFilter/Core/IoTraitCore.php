<?php

namespace eloquentFilter\QueryFilter\Core;

trait IoTraitCore
{
    /**
     * @param $out
     *
     * @return mixed
     */
    public function handelResponseFilter($out)
    {
        if (method_exists($this->builder->getModel(), 'ResponseFilter')) {
            return $this->builder->getModel()->ResponseFilter($out);
        }

        return $out;
    }

}
