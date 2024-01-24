<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent implements UserRepository
{
    public function update(array $attributes, $id): bool
    {
        return User::query()->where('id', '=', $id)
                            ->update($attributes);
    }
}
