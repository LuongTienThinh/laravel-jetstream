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

    /**
     * Create a product
     *
     * @param  array $attributes
     * @return void
     */
    public function create(array $attributes): void;

    /**
     * Update a product
     *
     * @param  array $attributes
     * @param  $id
     * @return bool
     */
    public function update(array $attributes, $id): bool;

    /**
     * Delete a product
     *
     * @param  $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Find a product by id
     *
     * @param  $id
     * @return mixed
     */
    public function findById($id): mixed;

    /**
     * Get list products by search content
     *
     * @param  string $search
     * @return Builder
     */
    public function filterSearch(string $search): Builder;

    /**
     * Insert category into products
     *
     * @return Builder
     */
    public function getProductWith(): Builder;

    /**
     * Paginate for the list of products
     *
     * @param  Builder  $listProduct
     * @param  int|null $page
     * @param  int|null $perPage
     * @return JsonResponse
     */
    public function productPagination(Builder $listProduct, int $page = null, int $perPage = null): JsonResponse;
}
