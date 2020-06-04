<?php

namespace Tests\Controllers;

use Tests\Models\User;

/**
 * Class UsersController.
 */
class UsersController
{
    /**
     * @param \eloquentFilter\QueryFilter\ModelFilters\ModelFilters $filters
     *
     * @return mixed
     */
    public static function filterUser($filters)
    {
        $users = User::filter($filters);

        return $users;
    }

    /**
     * @param \eloquentFilter\QueryFilter\ModelFilters\ModelFilters $filters
     *
     * @return mixed
     */
    public static function filterUserWith($filters, array $models)
    {
        $users = User::with($models)->filter($filters);

        return $users;
    }
}
