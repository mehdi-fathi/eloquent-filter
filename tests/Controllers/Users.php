<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 2/17/19
 * Time: 11:15 AM
 */

namespace Tests\Controllers;


use eloquentFilter\QueryFilter\modelFilters\modelFilters;
use Tests\Models\User;

class Users
{
    public static function filter_user(modelFilters $filters){


        $users = User::filter($filters)->orderByDesc('id')->get();

        return $users;
    }
}
