<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Post.
 */
class Post extends Model
{
    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var array
     */
    protected $guarded = [];
}
