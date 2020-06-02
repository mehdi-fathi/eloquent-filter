<?php

namespace eloquentFilter\QueryFilter;

use Illuminate\Support\Arr;

/**
 * Trait HelperFilter
 *
 * @package eloquentFilter\QueryFilter
 */
trait HelperFilter
{
    /**
     * @param array $arr
     *
     * @return bool
     */
    public function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
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
        $out = null;
        if (method_exists($this->builder->getModel(), $field)) {
            $out = Arr::dot($args, $field . '.');
        }
        return $out;
    }
}
