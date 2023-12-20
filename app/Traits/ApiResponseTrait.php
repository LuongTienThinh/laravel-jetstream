<?php

namespace App\Traits;

trait ApiResponseTrait 
{
    public function successResponse($data, $statusCode, $message)
    {
        return response()->json([
            'status' => $statusCode,
            'message'=> $message,
            'data'=> $data,
        ], $statusCode);
    }

    public function errorResponse($statusCode, $message)
    {
        return response()->json([
            'status' => $statusCode,
            'error' => $message,
        ], $statusCode);
    }
}

?>