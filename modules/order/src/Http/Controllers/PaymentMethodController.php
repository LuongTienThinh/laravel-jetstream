<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Order\Services\PaymentMethodService;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

class PaymentMethodController extends Controller
{
    use ApiResponseTrait;

    /**
     * The service payment method variable
     *
     * @var PaymentMethodService
     */
    protected PaymentMethodService $paymentMethodService;

    /**
     * Constructor function for PaymentMethodController
     *
     * @param PaymentMethodService $paymentMethodService
     */
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * Get list payment methods
     *
     * @return JsonResponse
     */
    #[Get(
        path: '/api/payment/method',
        operationId: "getListPaymentMethods",
        description: "Get list payment methods.",
        summary: "Get payment methods.",
        tags: ['payments'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 200),
                        new Property(property: "message", type: "string", example: "Get list payment methods success.")
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
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse($this->paymentMethodService->getPaymentMethods(), 200, "Get list payment methods success") ;
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
