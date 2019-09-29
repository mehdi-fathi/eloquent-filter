<?php

namespace Tests\Controllers;

use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Tests\Models\User;

/**
 * Class UsersController
 *
 * @package Tests\Controllers
 */
class UsersController
{
    /**
     * @param \eloquentFilter\QueryFilter\modelFilters\modelFilters $filters
     *
     * @return mixed
     */
    public static function filter_user(modelFilters $filters)
    {
        $users = User::filter($filters)->get();

        return $users;
    }
}
