<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepositoryEloquent;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Services\CartItemService;
use Modules\Order\Jobs\ProcessSendMail;
use Modules\Order\Services\OrderService;
use Modules\Order\Services\OrderItemService;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class OrderItemController extends Controller
{
    use ApiResponseTrait;

    /**
     * The service order variable
     *
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * The service order item variable
     *
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * The service cart item variable
     *
     * @var CartItemService
     */
    protected CartItemService $cartItemService;

    /**
     * The service product variable
     *
     * @var ProductRepositoryEloquent
     */
    protected ProductRepositoryEloquent $productService;

    /**
     * Constructor function for OrderItemController
     *
     * @param OrderService $orderService
     * @param OrderItemService $orderItemService
     * @param CartItemService $cartItemService
     * @param ProductRepositoryEloquent $productService
     */
    public function __construct(
        OrderService $orderService,
        OrderItemService $orderItemService,
        CartItemService $cartItemService,
        ProductRepositoryEloquent $productService
    )
    {
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
        $this->cartItemService = $cartItemService;
        $this->productService = $productService;
    }

    /**
     * Create new order and order items
     *
     * @param  Request $request
     * @return JsonResponse
     */
    #[Post(
        path: '/api/payment/create-order',
        operationId: "createOrder",
        description: "Create a order and add it into orders table.",
        summary: "Create a order",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "payment_method_id", type: "string", example: "1"),
                    new Property(properties: [
                        new Property(property: "cart_id", type: "string", example: "10"),
                        new Property(property: "product_id", type: "string", example: "1"),
                        new Property(property: "quantity", type: "int", example: 1),
                        new Property(property: "total_price", type: "string", example: 19900000),
                        new Property(property: "quantity_in_stock", type: "string", example: "15"),
                    ])
                ]
            )
        ),
        tags: ['orders'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Create a order success.")
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
    public function store(Request $request): JsonResponse
    {
        try {
            if (Auth::check()) {
                $userId = Auth::user()->id;
                $orderDetails = $request->all();

                $order = $this->orderService->create([
                    'user_id' => $userId,
                    'payment_method_id' => $orderDetails['payment_method_id'],
                ]);

                foreach ($orderDetails['products'] as $item) {
                    $this->orderItemService->create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total_price'],
                    ]);
                    $this->cartItemService->deleteCartItem($item['cart_id'], $item['product_id']);
                    $this->productService->update([
                        'quantity' => $item['quantity_in_stock'] - $item['quantity']
                    ], $item['product_id']);
                }

                ProcessSendMail::dispatch(Auth::user()->email, $order, Auth::user());

                return $this->successResponse(null, 200, 'Create order success');
            }
            return $this->errorResponse(200, 'Create order fail');
        } catch (Exception $e) {
            return $this->errorResponse(200, $e->getMessage());
        }
    }
}
