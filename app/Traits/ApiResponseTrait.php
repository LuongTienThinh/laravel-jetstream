<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait 
{
    /**
     * Generate a success response
     * 
     * @param  mixed  $data
     * @param  int    $statusCode
     * @param  string $message
     * @return JsonResponse
     */
    public function successResponse($data, $statusCode, $message): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message'=> $message,
            'data'=> $data,
        ], $statusCode);
    }
    
    /**
     * Generate a error response
     * 
     * @param  int    $statusCode
     * @param  string $message
     * @return JsonResponse
     */
    public function errorResponse($statusCode, $message): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'error' => $message,
        ], $statusCode);
    }
}

?>