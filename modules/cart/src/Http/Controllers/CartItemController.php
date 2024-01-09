<?php

namespace Modules\Cart\src\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Modules\Cart\src\Http\Requests\UpdateCartRequest;
use Modules\Cart\src\Services\CartItemRepository;
use Exception;
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

    public CartItemRepository $cartItemRepository;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Add a product to cart
     *
     * @param  UpdateCartRequest $request
     * @return JsonResponse
     */
    #[Post(
        path: '/api/cart/create',
        operationId: "addProductToCart",
        description: "Add a product to cart of user.",
        summary: "Add product to cart",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "cart_id", type: "string", example: "10"),
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
                        new Property(property: "message", type: "string", example: "Add product to cart success.")
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
            $this->cartItemRepository->create($request->validated());

            $message = "Add product to cart success";
            return $this->successResponse(null, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Update a product in cart
     *
     * @param  UpdateCartRequest $request
     * @param  string $cartId
     * @param  string $productId
     * @return JsonResponse
     */
    #[Put(
        path: '/api/cart/edit/{cart_id}-{product_id}',
        operationId: "updateCartProduct",
        description: "Update a product from cart of user.",
        summary: "Update a product",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "cart_id", type: "string", example: "10"),
                    new Property(property: "product_id", type: "string", example: "1"),
                    new Property(property: "quantity", type: "int", example: "1"),
                    new Property(property: "total_price", type: "float", example: "19900000"),
                ]
            )
        ),
        tags: ['carts'],
        parameters: [
            new Parameter(
                name: "cart_id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
                )
            ),
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
                        new Property(property: "message", type: "string", example: "Update a product success.")
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
    public function update(UpdateCartRequest $request, string $cartId, string $productId): JsonResponse
    {
        try {
            $this->cartItemRepository->updateCartProduct($request->validated(), $cartId, $productId);

            $message = "Update a product success";
            return $this->successResponse(null, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Remove a product from cart
     *
     * @param  string $cartId
     * @param  string $productId
     * @return JsonResponse
     */
    #[Delete(
        path: '/api/cart/delete/{cart_id}-{product_id}',
        operationId: "removeCartProduct",
        description: "Remove a product from cart of user.",
        summary: "Remove product from cart",
        tags: ['carts'],
        parameters: [
            new Parameter(
                name: "cart_id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
                )
            ),
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
    public function destroy(string $cartId, string $productId): JsonResponse
    {
        try {
            $this->cartItemRepository->deleteCartProduct($cartId, $productId);

            $message = "Remove product success";
            return $this->successResponse(null, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
