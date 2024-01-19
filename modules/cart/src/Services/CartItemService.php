<?php

namespace Modules\Cart\Services;

use App\Traits\ApiResponseTrait;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Modules\Cart\Http\Requests\UpdateCartRequest;
use Modules\Cart\Models\CartItem;
use Modules\Cart\Services\Interfaces\CartItemInterface;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CartItemService implements CartItemInterface
{
    use ApiResponseTrait;

    public function create(array $attributes): void
    {
        CartItem::query()->create($attributes);
    }

    public function updateCartItem(array $attributes, string $cartId, string $cartItemId): int
    {
        return CartItem::query()->where('cart_id', '=', $cartId)
                                ->where('product_id', '=', $cartItemId)
                                ->update($attributes);
    }

    public function updateCartItemExistedInCart(array $attributes, string $cartId, string $cartItemId): int
    {
        $product = CartItem::query()->where('cart_id', '=', $cartId)
                                    ->where('product_id', '=', $cartItemId)
                                    ->first();

        $attributes['quantity'] += $product->quantity;

        return CartItem::query()->where('cart_id', '=', $cartId)
                                ->where('product_id', '=', $cartItemId)
                                ->update($attributes);
    }

    public function deleteCartItem(string $cartId, string $cartItemId): mixed
    {
        return CartItem::query()->where('cart_id', '=', $cartId)
                                ->where('product_id', '=', $cartItemId)
                                ->delete();
    }

    public function getCartItemByCartId(string $id): Builder
    {
        return $this->getCartItemRelationship(CartItem::query()->where('cart_id', '=', $id));
    }

    public function getCartItemRelationship(Builder $cartItems): Builder
    {
        return $cartItems->with(['cart', 'product']);
    }

    public function handleCartDataNoLogin(array $cartItems): array
    {
        return [
            'products' => array_map(function($item) {
                $product = Product::query()->where('id', '=', $item->product_id)->first();

                $item->base_price = $product->price;
                $item->name = $product->name;
                $item->quantity_in_stock = $product->quantity;

                return $item;
            }, $cartItems)
        ];
    }

    public function handleDataBeforeResponse(Collection $products): array
    {
        return [
            'products' => $products->map(function($item) {
                $item->base_price = $item->product->price;
                $item->name = $item->product->name;
                $item->quantity_in_stock = $item->product->quantity;
                unset($item->cart);
                unset($item->product);
                return $item;
            })
        ];
    }

    public function isCartItemInCart(string $cartId, string $productId): Bool
    {
        $product = CartItem::query()->where('cart_id', '=', $cartId)
                                    ->where('product_id', '=', $productId)
                                    ->first();
        return $product instanceof CartItem;
    }

    public function getCartItemsNoLogin(Request $request): JsonResponse
    {
        if ($request->hasCookie('cart-list')) {
            $products = json_decode($request->cookie('cart-list'));

            $products = $this->handleCartDataNoLogin($products);

            return $this->successResponse($products, 200, "Get list cart items success");
        }
        return $this->successResponse([], 200, "Get list cart items success");
    }

    public function getCartItemsLogged(Request $request): JsonResponse
    {
        $cartId = Auth::user()->cart->id;

        if ($request->hasCookie('cart-list')) {
            $products = json_decode($request->cookie('cart-list'));

            $products = $this->handleCartDataNoLogin($products);

            foreach ($products['products'] as $item) {
                $cartItem = [
                    'cart_id' => $cartId,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price
                ];
                if($this->isCartItemInCart($cartId, $item->product_id)) {
                    $this->updateCartItemExistedInCart($cartItem, $cartId, $item->product_id);
                } else {
                    $this->create($cartItem);
                }
            }
        }
        $products = $this->getCartItemByCartId($cartId)->get();
        $data = $this->handleDataBeforeResponse($products);

        return $this->successResponse($data, 200, "Get list cart items success")
                    ->cookie(Cookie::forget('cart-list'));
    }

    public function addCartItemNoLogin(UpdateCartRequest $request): JsonResponse
    {
        $cartList = [];
        if ($request->hasCookie('cart-list')) {
            $cartList = array_merge($cartList, json_decode($request->cookie('cart-list')));
        }

        $cartItem = $request->validated();

        array_map(function ($item) use($cartItem) {
            if ($item->product_id == $cartItem['product_id']) {
                $item->quantity += $cartItem['quantity'];
                $item->total_price += $cartItem['total_price'];
            }
            return $item;
        }, $cartList);

        if ($cartList == json_decode($request->cookie('cart-list'))) {
            $cartList[] = $cartItem;
        }

        $message = "Add cart item to cart success";
        return $this->successResponse(null, 200, $message)
                    ->cookie('cart-list', json_encode($cartList), 60);
    }

    public function addCartItemLogged(UpdateCartRequest $request): JsonResponse
    {
        $cartId = Auth::user()->cart->id;
        $productId = $request->get('product_id');

        if($this->isCartItemInCart($cartId, $productId)) {
            $this->updateCartItemExistedInCart($request->validated(), $cartId, $productId);
        } else {
            $this->create($request->validated());
        }

        $message = "Add cart item to cart success";
        return $this->successResponse(null, 200, $message);
    }

    public function updateCartItemNoLogin(UpdateCartRequest $request, string $cartItemId): JsonResponse
    {
        $cartList = json_decode($request->cookie('cart-list'));

        $cartItem = $request->validated();

        array_map(function ($item) use($cartItem) {
            if ($item->product_id == $cartItem['product_id']) {
                $item->quantity = $cartItem['quantity'];
                $item->total_price = $cartItem['total_price'];
            }
            return $item;
        }, $cartList);

        $message = "Update a cart item success";
        return $this->successResponse(null, 200, $message)
                    ->cookie('cart-list', json_encode($cartList), 60);
    }

    public function updateCartItemLogged(UpdateCartRequest $request, string $cartItemId): JsonResponse
    {
        $cartId = Auth::user()->cart->id;
        $this->updateCartItem($request->validated(), $cartId, $cartItemId);

        $message = "Update a cart item success";
        return $this->successResponse(null, 200, $message);
    }

    public function deleteCartItemNoLogin(Request $request, string $productId): JsonResponse
    {
        $cartList = json_decode($request->cookie('cart-list'));

        $cartList = array_filter($cartList, function ($item) use ($productId) {
            return $item->product_id != $productId;
        });

        $message = "Remove a cart item success";
        return $this->successResponse(null, 200, $message)
                    ->cookie('cart-list', json_encode($cartList), 60);
    }

    public function deleteCartItemLogged(string $productId): JsonResponse
    {
        $cartId = Auth::user()->cart->id;
        $this->deleteCartItem($cartId, $productId);

        $message = "Remove a cart item success";
        return $this->successResponse(null, 200, $message);
    }
}
