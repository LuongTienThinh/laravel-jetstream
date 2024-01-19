<?php

namespace Modules\Cart\Services\Interfaces;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface CartInterface
{
    /**
     * Find a product by id
     *
     * @param  $id
     * @return mixed
     */
    public function findById($id): mixed;
}
