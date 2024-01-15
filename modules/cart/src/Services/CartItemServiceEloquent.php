<?php

namespace Modules\Cart\Services;

use App\Traits\ApiResponseTrait;
use App\Validators\ProductValidator;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Modules\Cart\Models\CartItem;
use Modules\Cart\Services\Interfaces\CartItemService;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CartItemServiceEloquent extends BaseRepository implements CartItemService
{
    use ApiResponseTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CartItem::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function create(array $attributes): void
    {
        $this->model->create($attributes);
    }

    public function updateCartProduct(array $attributes, string $cartId, string $productId): mixed
    {
        return $this->model->where('cart_id', '=', $cartId)
                           ->where('product_id', '=', $productId)
                           ->update($attributes);
    }

    public function deleteCartProduct(string $cartId, string $productId): mixed
    {
        return $this->model->where('cart_id', '=', $cartId)
                           ->where('product_id', '=', $productId)
                           ->delete();
    }

    public function filterSearch(string $search): Builder
    {
        return $this->model->where('name', 'like', '%' . $search . '%')->with(['cart', 'product']);
    }

    public function getCartProductWith(): Builder
    {
        return $this->model->with(['cart', 'product']);
    }

    public function handleCartDataNoLogin(array $listProduct): array
    {
        return array_map(function($item) {
            $product = Product::query()->find($item->product_id)->first();

            $item->base_price = $product->price;
            $item->name = $product->name;
            $item->quantity_in_stock = $product->quantity;


            return $item;
        }, $listProduct);
    }

    public function cartProductPagination(Builder $listProduct, int $page = null, int $perPage = null): JsonResponse
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 5;

        $products = $listProduct->skip(($page - 1) * $perPage)->take($perPage)->get();
        $nextProducts = $listProduct->skip($page * $perPage)->take($perPage)->get();

        $prev = $page > 1 && $products->isNotEmpty();
        $next = $nextProducts->isNotEmpty();

        try {
            $listProducts = $products->map(function($item, $index) use($page, $perPage) {
                $item->base_price = $item->product->price;
                $item->name = $item->product->name;
                $item->quantity_in_stock = $item->product->quantity;
                $item->no = ($page - 1) * $perPage + 1 + $index;
                unset($item->cart);
                unset($item->product);
                return $item;
            });

            $newData = [
                'products'=> $listProducts,
                'prev' => $prev,
                'next' => $next
            ];

            $message = 'Get list products successfully';

            return $this->successResponse($newData, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
