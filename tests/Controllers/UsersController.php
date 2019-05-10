<?php

namespace Tests\Controllers;

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Tests\Models\User;

class UsersController
{
    public static function filter_user(modelFilters $filters)
    {
        $users = User::filter($filters)->get();

        return $users;
    }
}
