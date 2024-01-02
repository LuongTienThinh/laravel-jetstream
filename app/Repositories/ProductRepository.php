<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface ProductRepository extends RepositoryInterface
{
    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function findById(string $id);

    public function filterSearch(string $search);

    public function getProductWith();
}
