<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Services\OrderItemService;
use Modules\Order\Services\OrderService;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class OrderController extends Controller
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
     * Constructor function for OrderController
     *
     * @param OrderService $orderService
     * @param OrderItemService $orderItemService
     */
    public function __construct(OrderService $orderService, OrderItemService $orderItemService)
    {
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
    }

    /**
     * Get list orders
     *
     * @return JsonResponse
     */
    #[Get(
        path: '/api/order/list-order',
        operationId: "getListOrders",
        description: "Get list orders of user.",
        summary: "Get list orders",
        tags: ['orders'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 200),
                        new Property(property: "message", type: "string", example: "Get list orders success.")
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
    public function getListOrders(): JsonResponse
    {
        $userId = Auth::user()->id;
        try {
            $listOrder = $this->orderService->getListOrders($userId);

            return $this->successResponse($listOrder, 200, 'Get list orders success');
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Get order details
     *
     * @param  string $orderId
     * @return JsonResponse
     */
    #[Get(
        path: '/api/order/order-detail/{id}',
        operationId: "getOrderDetails",
        description: "Get detail of the order.",
        summary: "Get order detail",
        tags: ['orders'],
        parameters: [
            new Parameter(
                name: "orderId",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
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
                        new Property(property: "message", type: "string", example: "Get order detail success.")
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
    public function getOrderDetails(string $orderId): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($orderId);

            return $this->successResponse($order, 200, 'Get list orders success');
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Show view checkout
     *
     * @return View|Application|Factory|string|null
     */
    public function viewCheckout(): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::checkout')->with('user', $user);
    }

    /**
     * Show view order
     *
     * @return View|Application|Factory|string|null
     */
    public function viewOrder(): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::order')->with('user', $user);
    }

    /**
     * Show view order detail
     *
     * @param  string $orderId
     * @return View|Application|Factory|string|null
     */
    public function viewOrderDetail(string $orderId): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::order-detail')->with('orderId', $orderId);
    }
}
