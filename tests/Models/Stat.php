<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{

    use Filterable;
    /**
     * @var array
     */
    private static $whiteListFilter = [
        'type',
        'national_code',
    ];


    public function ResponseFilter($out)
    {
        $data['data'] = $out;

        return $data;
    }


    /**
     * @var array
     */
    private $aliasListFilter = [
        'national_code' => 'code',
    ];

}
