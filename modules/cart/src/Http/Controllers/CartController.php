<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Services\CartService;
use Modules\Cart\Services\CartItemService;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

class CartController extends Controller
{
    use ApiResponseTrait;

    /**
     * The cart service variable
     *
     * @var CartService
     */
    public CartService $cartService;

    /**
     * The cart item service variable
     *
     * @var CartItemService
     */
    public CartItemService $cartItemService;

    /**
     * Constructor function for CartController
     *
     * @param CartService $cartService
     * @param CartItemService $cartItemService
     */
    public function __construct(CartService $cartService, CartItemService $cartItemService)
    {
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
    }

    /**
     * Get list cart items in cart of user
     *
     * @param  Request $request
     * @return JsonResponse
     */
    #[Get(
        path: '/api/cart',
        operationId: "getListCartItem",
        description: "Get list items in cart of user.",
        summary: "Get list cart items",
        tags: ['carts'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 200),
                        new Property(property: "message", type: "string", example: "Get list items success.")
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
            if (!Auth::check()) {
                return $this->cartItemService->getCartItemsNoLogin($request);
            } else {
                return $this->cartItemService->getCartItemsLogged($request);
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Show view cart
     *
     * @return View|Application|Factory|string|null
     */
    public function viewCartDetail(): View|Application|Factory|string|null
    {
        return view('Modules-Cart::cart-detail');
    }
}
