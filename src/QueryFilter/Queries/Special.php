<?php

namespace eloquentFilter\QueryFilter\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Special.
 */
class Special extends BaseClause
{
    /**
     * @var array
     */
    public static $reserve_param = [
        'f_params' => [
            'limit',
            'orderBy',
        ],
    ];

    /**
     * @param $query
     *
     * @throws \Exception
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        foreach ($this->values as $key => $param_value) {
            if (!in_array($key, self::$reserve_param['f_params'])) {
                throw new \Exception("$key is not in f_params array.");
            }
            if (is_array($param_value)) {
                $this->values['orderBy']['field'] = explode(',', $this->values['orderBy']['field']);
                foreach ($this->values['orderBy']['field'] as $order_by) {
                    $query->orderBy($order_by, $this->values['orderBy']['type']);
                }
            } else {
                $query->limit($param_value);
            }
        }

        return $query;
    }
}
