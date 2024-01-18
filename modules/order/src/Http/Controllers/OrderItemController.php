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
use Modules\Order\Services\OrderService;
use Modules\Order\Services\OrderItemService;

class OrderItemController extends Controller
{
    use ApiResponseTrait;

    public OrderService $orderService;

    public OrderItemService $orderItemService;

    public CartItemService $cartItemService;

    public ProductRepositoryEloquent $productService;

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
//                    dd($item);
                    $this->orderItemService->create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total_price'],
                    ]);
                    $this->cartItemService->deleteCartProduct($item['cart_id'], $item['product_id']);
                    $this->productService->update([
                        'quantity' => $item['quantity_in_stock'] - $item['quantity']
                    ], $item['product_id']);
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
//  - tạo order (v)
//  - thêm sản phẩm vào order (v)
//  - xoá sản phẩm khỏi cart (v)
//  - giảm số lượng sản phẩm (v)
