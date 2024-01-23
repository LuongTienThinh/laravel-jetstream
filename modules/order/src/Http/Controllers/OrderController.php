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

class OrderController extends Controller
{
    use ApiResponseTrait;

    public OrderService $orderService;
    public OrderItemService $orderItemService;

    public function __construct(OrderService $orderService, OrderItemService $orderItemService)
    {
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
    }

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
     * Display order view
     *
     * @return View|Application|Factory|string|null
     */
    public function viewCheckout(): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::checkout')->with('user', $user);
    }

    public function viewOrder(): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::order')->with('user', $user);
    }

    public function viewOrderDetail(string $orderId): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::order-detail')->with('orderId', $orderId);
    }
}
