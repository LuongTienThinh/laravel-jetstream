<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Services\CartService;
use Modules\Cart\Services\CartItemService;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CartController extends Controller
{
    use ApiResponseTrait;

    public CartService $cartService;
    public CartItemService $cartItemService;

    public function __construct(CartService $cartService, CartItemService $cartItemService)
    {
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
    }

    /**
     * Get list products in cart of user
     *
     * @param  Request $request
     * @return JsonResponse
     */
    #[Get(
        path: '/api/cart/{id}',
        operationId: "getListCartProduct",
        description: "Get list products by search (default: empty) for each page (default: 1) in cart of user.",
        summary: "Get list cart products",
        tags: ['carts'],
        parameters: [
            new Parameter(
                name: "search",
                in: "query",
                required: false,
                schema: new Schema(
                    type: "string",
                )
            ),
            new Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new Schema(
                    type: "int",
                )
            ),
            new Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string",
                )
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 200),
                        new Property(property: "message", type: "string", example: "Get list products successfully.")
                    ]
                )
            ),
            new Response(
                response: 500,
                description: 'Error',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 500),
                        new Property(property: "message", type: "string", example: "Internal server error.")
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page');

            if (!Auth::check()) {
                if ($request->hasCookie('cart-list')) {
                    $products = json_decode($request->cookie('cart-list'));

                    $products = $this->cartItemService->handleCartDataNoLogin($products);

                    return $this->successResponse($products, 200, "Get list products successfully");
                }
                return $this->successResponse([], 200, "Get list products successfully");
            } else {
                $cartId = Auth::user()->cart->id;
                $products = $this->cartItemService->getCartProductByCartId($cartId)->get();

                $data = $this->cartItemService->handleDataBeforeResponse($products);

                return $this->successResponse($data, 200, "Get list products successfully");
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
