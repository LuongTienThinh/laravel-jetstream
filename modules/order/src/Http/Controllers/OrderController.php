<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Order\Services\OrderService;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->$orderService = $orderService;
    }
}
