<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Exceptions\DBFilterException;
use eloquentFilter\QueryFilter\Exceptions\EloquentFilterException;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;
use Illuminate\Support\Facades\DB;

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
     * @return Builder
     * @throws \Exception
     *
     */
    public function apply($query)
    {
        foreach ($this->values as $key => $param_value) {
            if (!in_array($key, self::$reserve_param['f_params'])) {
                throw new EloquentFilterException("$key is not in f_params array.", 2);
            }
            if (is_array($param_value)) {
                $this->values['orderBy']['field'] = explode(',', $this->values['orderBy']['field']);
                foreach ($this->values['orderBy']['field'] as $order_by) {
                    $query->orderBy($order_by, $this->values['orderBy']['type']);
                }
            } else {
                if (config('eloquentFilter.max_limit') > 0) {
                    $param_value = min(config('eloquentFilter.max_limit'), $param_value);
                }
                $query->limit($param_value);
            }
        }

        return $query;
    }
}
