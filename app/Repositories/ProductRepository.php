<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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

    public function findById($id);

    public function filterSearch(string $search);

    public function getProductWith();

    public function productPagination(Builder $listProduct, int $page = null, int $perPage = null);
}
