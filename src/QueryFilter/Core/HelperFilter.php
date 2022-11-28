<?php

namespace eloquentFilter\QueryFilter\Core;

use Illuminate\Support\Arr;

/**
 * Trait HelperFilter.
 */
trait HelperFilter
{
    /**
     * @param       $field
     * @param array $args
     *
     * @return array|null
     */
    public static function convertRelationArrayRequestToStr($field, array $args): ?array
    {
        $arg_last = Arr::last($args);

        if (is_array($arg_last)) {
            $out = Arr::dot($args, $field.'.');
            if (!self::isAssoc($arg_last)) {
                $out = Arr::dot($args, $field.'.');
                foreach ($out as $key => $item) {
                    $index = $key;
                    for ($i = 0; $i <= 9; $i++) {
                        $index = rtrim($index, '.'.$i);
                    }
                    $new[$index][] = $out[$key];
                }
                $out = $new;
            } else {
                $key_search_start = $field.'.'.key($args).'.start';
                $key_search_end = $field.'.'.key($args).'.end';

                if (Arr::exists($out, $key_search_start) && Arr::exists($out, $key_search_end)) {
                    foreach ($args as $key => $item) {
                        $new[$field.'.'.$key] = $args[$key];
                    }
                    $out = $new;
                }
            }
        } else {
            $out = Arr::dot($args, $field.'.');
        }

        return $out;
    }

    /**
     * @param array $arr
     *
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param $request
     * @param null $keys
     *
     * @return array
     */
    public static function array_slice_keys($request, $keys = null): array
    {
        $request = (array) $request;

        return array_intersect_key($request, array_fill_keys($keys, '1'));
    }
}
