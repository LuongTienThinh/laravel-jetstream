<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Http\Requests\UpdateCartRequest;
use Modules\Cart\Services\CartItemService;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CartItemController extends Controller
{
    use ApiResponseTrait;

    public CartItemService $cartItemService;

    public function __construct(CartItemService $cartItemService)
    {
        $this->cartItemService = $cartItemService;
    }

    /**
     * Add a cart item to cart
     *
     * @param  UpdateCartRequest $request
     * @return JsonResponse
     */
    #[Post(
        path: '/api/cart/create',
        operationId: "addCartItem",
        description: "Add a cart item to cart.",
        summary: "Add a cart item.",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "product_id", type: "string", example: "1"),
                    new Property(property: "quantity", type: "int", example: "1"),
                    new Property(property: "total_price", type: "float", example: "19900000"),
                ]
            )
        ),
        tags: ['carts'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Add cart item to cart success.")
                    ]
                )
            ),
            new Response(
                response: 500,
                description: 'Error',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Internal server error.")
                    ]
                )
            ),
        ]
    )]
    public function store(UpdateCartRequest $request): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return $this->cartItemService->addCartItemNoLogin($request);
            } else {
                return $this->cartItemService->addCartItemLogged($request);
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Update a cart item in cart
     *
     * @param  UpdateCartRequest $request
     * @param  string $cartItemId
     * @return JsonResponse
     */
    #[Put(
        path: '/api/cart/edit/{cart_item_id}',
        operationId: "updateCartItem",
        description: "Update a cart item in cart.",
        summary: "Update a cart item.",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "product_id", type: "string", example: "1"),
                    new Property(property: "quantity", type: "int", example: "1"),
                    new Property(property: "total_price", type: "float", example: "19900000"),
                ]
            )
        ),
        tags: ['carts'],
        parameters: [
            new Parameter(
                name: "cart_item_id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
                )
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Update a cart item success.")
                    ]
                )
            ),
            new Response(
                response: 500,
                description: 'Error',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Internal server error.")
                    ]
                )
            ),
        ]
    )]
    public function update(UpdateCartRequest $request, string $cartItemId): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return $this->cartItemService->updateCartItemNoLogin($request, $cartItemId);
            } else {
                return $this->cartItemService->updateCartItemLogged($request, $cartItemId);
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Remove a cart item from cart
     *
     * @param  Request $request
     * @param  string $productId
     * @return JsonResponse
     */
    #[Delete(
        path: '/api/cart/delete/{cart_item_id}',
        operationId: "removeCartItem",
        description: "Remove a cart item from cart.",
        summary: "Remove a cart item.",
        tags: ['carts'],
        parameters: [
            new Parameter(
                name: "product_id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
                )
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Remove product success.")
                    ]
                )
            ),
            new Response(
                response: 500,
                description: 'Error',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Internal server error.")
                    ]
                )
            ),
        ]
    )]
    public function destroy(Request $request, string $productId): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return $this->cartItemService->deleteCartItemNoLogin($request, $productId);
            } else {
                return $this->cartItemService->deleteCartItemLogged($productId);
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
