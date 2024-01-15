<?php

namespace Modules\Cart\Services\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface CartItemService extends RepositoryInterface
{

    /**
     * Create a product
     *
     * @param  array $attributes
     * @return void
     */
    public function create(array $attributes): void;

    /**
     * Delete a product by cart_id and product_id
     *
     * @param  array $attributes
     * @param  string $cartId
     * @param  string $productId
     * @return mixed
     */
    public function updateCartProduct(array $attributes, string $cartId, string $productId): mixed;

    /**
     * Delete a product by cart_id and product_id
     *
     * @param  string $cartId
     * @param  string $productId
     * @return mixed
     */
    public function deleteCartProduct(string $cartId, string $productId): mixed;

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
    public function getCartProductWith(): Builder;

    /**
     * Handle data of list products before response api
     *
     * @param  array $listProduct
     * @return array
     */
    public function handleCartDataNoLogin(array $listProduct): array;


    /**
     * Paginate for the list of products
     *
     * @param  Builder  $listProduct
     * @param  int|null $page
     * @param  int|null $perPage
     * @return JsonResponse
     */
    public function cartProductPagination(Builder $listProduct, int $page = null, int $perPage = null): JsonResponse;
}
