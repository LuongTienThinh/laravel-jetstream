<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Services\CartItemService;
use Modules\Order\Services\OrderService;
use Modules\Order\Services\OrderItemService;

class OrderItemController extends Controller
{
    use ApiResponseTrait;

    public OrderService $orderService;

    public OrderItemService $orderItemService;

    public CartItemService $cartItemService;

    public function __construct(OrderService $orderService, OrderItemService $orderItemService, CartItemService $cartItemService)
    {
        $this->orderService = $orderService;
        $this->orderItemService = $orderItemService;
        $this->cartItemService = $cartItemService;
    }

    public function store(Request $request)
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
                    dd($item);
                }

                return $this->successResponse(null, 200, 'Create success');
            }
            return $this->errorResponse(200, 'Create fail');
        } catch (Exception $e) {
            return $this->errorResponse(200, $e->getMessage());
        }
    }
}
//  Khi thanh toán, thực hiện các công việc:
//  - tạo order
//  - thêm sản phẩm vào order
//  - xoá sản phẩm khỏi cart
//  - giảm số lượng sản phẩm
