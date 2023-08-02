<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function successResponse($data = [], $message = '', $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], $status);
    }

    public function errorResponse($message = '', $data = [], $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message
        ], $status);
    }

    /**
     * @return JsonResponse
     */
    public function unauthorized(): JsonResponse
    {
        return $this->errorResponse('Unauthorized', [], 401);
    }
}
