<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use Filterable;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'name',
        'model',
        'count_driver',
        'created_at',
    ];

    protected $black_list_detections = [
        'WhereCondition',
    ];

    public function foo()
    {
        return $this->belongsTo(Category::class);
    }

    public function address()
    {
        return $this->belongsTo(Category::class, 'foo_id');
    }

    public function activeFoo()
    {
        return $this->belongsTo(Category::class, 'foo_id')->where('active', true);
    }
}
