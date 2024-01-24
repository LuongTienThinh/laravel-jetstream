<?php

namespace App\Repositories;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories;
 */
interface UserRepository
{
    /**
     * Update a user
     *
     * @param  array $attributes
     * @param  $id
     * @return bool
     */
    public function update(array $attributes, $id): bool;
}
