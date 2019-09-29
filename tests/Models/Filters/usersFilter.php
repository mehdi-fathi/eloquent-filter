<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 2/27/19
 * Time: 4:32 PM.
 */

namespace Tests\Models\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait usersFilter
 *
 * @package Tests\Models\Filters
 */
trait usersFilter
{
    public function username_like(Builder $builder, $value)
    {
        return $builder->where('username', 'like', '%'.$value.'%');
    }
}
