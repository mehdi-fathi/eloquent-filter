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

    /**
     * @var array
     */
    private $aliasListFilter = [
        'national_code' => 'code',
    ];

    public function getResponseFilter($out)
    {
        $data['data'] = $out;

        return $data;
    }

}
