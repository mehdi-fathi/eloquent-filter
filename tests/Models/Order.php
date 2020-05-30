<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Post.
 */
class Order extends Model
{
    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $guarded = [];
}
