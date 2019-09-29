<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoriesPosts
 *
 * @package Tests\Models
 */
class CategoriesPosts extends Model
{
    /**
     * @var string
     */
    protected $table = 'categories_posts';

    /**
     * @var array
     */
    protected $guarded = [];
}
