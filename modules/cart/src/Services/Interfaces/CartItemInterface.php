<?php

namespace Modules\Cart\Services\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Cart\Http\Requests\UpdateCartRequest;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface CartItemInterface
{

    /**
     * Create a cart item
     *
     * @param  array $attributes
     * @return void
     */
    public function create(array $attributes): void;

    /**
     * Update a cart item by cart id and cart item id
     *
     * @param  array  $attributes
     * @param  string $cartId
     * @param  string $cartItemId
     * @return mixed
     */
    public function updateCartItem(array $attributes, string $cartId, string $cartItemId): int;

    /**
     * Update cart items which is existed in cart
     *
     * @param  array  $attributes
     * @param  string $cartId
     * @param  string $cartItemId
     * @return mixed
     */
    public function updateCartItemExistedInCart(array $attributes, string $cartId, string $cartItemId): int;

    /**
     * Delete a cart item by cart id and cart item id
     *
     * @param  string $cartId
     * @param  string $cartItemId
     * @return mixed
     */
    public function deleteCartItem(string $cartId, string $cartItemId): mixed;

    /**
     * Get list cart items by cart id
     *
     * @param  string $id
     * @return Builder
     */
    public function getCartItemByCartId(string $id): Builder;

    /**
     * Insert relationships into cart items
     *
     * @param  Builder $cartItems
     * @return Builder
     */
    public function getCartItemRelationship(Builder $cartItems): Builder;

    /**
     * Handle data of list cart items before response api
     *
     * @param  array $cartItems
     * @return array
     */
    public function handleCartDataNoLogin(array $cartItems): array;

    /**
     * Handle data of list cart items before response api
     *
     * @param  Collection $products
     * @return array
     */
    public function handleDataBeforeResponse(Collection $products): array;

    /**
     * @param  string $cartId
     * @param  string $productId
     * @return bool
     */
    public function isCartItemInCart(string $cartId, string $productId): Bool;

    /**
     * Get list cart items of guest
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function getCartItemsNoLogin(Request $request): JsonResponse;

    /**
     * Get list cart items of user
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function getCartItemsLogged(Request $request): JsonResponse;

    /**
     * Add a cart item to cart of guest
     *
     * @param  UpdateCartRequest $request
     * @return JsonResponse
     */
    public function addCartItemNoLogin(UpdateCartRequest $request): JsonResponse;

    /**
     * Add a cart item to cart of user
     *
     * @param  UpdateCartRequest $request
     * @return JsonResponse
     */
    public function addCartItemLogged(UpdateCartRequest $request): JsonResponse;

    /**
     * Update a cart item in cart of guest
     *
     * @param  UpdateCartRequest $request
     * @param  string $cartItemId
     * @return JsonResponse
     */
    public function updateCartItemNoLogin(UpdateCartRequest $request, string $cartItemId): JsonResponse;

    /**
     * Update a cart item in cart of user
     *
     * @param  UpdateCartRequest $request
     * @param  string $cartItemId
     * @return JsonResponse
     */
    public function updateCartItemLogged(UpdateCartRequest $request, string $cartItemId): JsonResponse;

    /**
     * Delete a cart item in cart of guest
     *
     * @param  Request $request
     * @param  string $productId
     * @return JsonResponse
     */
    public function deleteCartItemNoLogin(Request $request, string $productId): JsonResponse;

    /**
     * Delete a cart item in cart of user
     *
     * @param  string $productId
     * @return JsonResponse
     */
    public function deleteCartItemLogged(string $productId): JsonResponse;
}
