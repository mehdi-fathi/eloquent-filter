<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 *
 * @package Tests\Models
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
