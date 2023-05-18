<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class Category extends Model
{
    use Filterable;
    use SoftDeletes;

    /**
     * @var array
     */
    private static $whiteListFilter = [
        'title',
        'count_posts',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function baz(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stat::class);
    }

    /**
     * @param array|null $request
     * @return mixed
     */
    public function serializeRequestFilter(?array $request)
    {
        if (!empty($request['new_title'])) {
            foreach ($request['new_title'] as &$item) {
                $item = trim($item, '__');
            }
            $request['title'] = $request['new_title'];
            unset($request['new_title']);
        }

        return $request;
    }

    /**
     * This is a sample custom query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sample_like(Builder $builder, $value)
    {
        return $builder->where('title', 'like', '%'.$value.'%');
    }
}
