<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Order\Services\PaymentMethodService;

class PaymentMethodController extends Controller
{
    use ApiResponseTrait;

    public PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index(): JsonResponse
    {
        try {
            return $this->successResponse($this->paymentMethodService->getPaymentMethods(), 200, "Get list payment methods success") ;
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
