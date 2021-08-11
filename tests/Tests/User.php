<?php

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Filterable;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function modelFilter()
    {
        return $this->provideFilter(UserFilter::class);
    }
}
