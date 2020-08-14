<?php


namespace eloquentFilter\QueryFilter\Queries;


use Illuminate\Database\Eloquent\Builder;

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

    public function apply($query): Builder
    {

        foreach ($this->values as $key => $param) {
            if (!in_array($key, self::$reserve_param['f_params'])) {
                throw new \Exception("$key is not in f_params array."); //TODO make exception test for it
            }
            if (is_array($param)) {
                $query->orderBy($this->values['orderBy']['field'], $this->values['orderBy']['type']);
            } else {
                $query->limit($this->values);

            }
        }
        return $query;


    }

}
