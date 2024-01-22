<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Services\OrderService;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display order view
     *
     * @return View|Application|Factory|string|null
     */
    public function viewOrder(): View|Application|Factory|string|null
    {
        $user = Auth::user();
        return view('Modules-Order::checkout')->with('user', $user);
    }
}
