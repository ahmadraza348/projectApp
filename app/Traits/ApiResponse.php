<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Build a successful JSON response.
     */
    protected function successResponse(mixed $data = null, string $message = null, int $code = 200): JsonResponse
    {
        // array_filter removes null values so we don't send empty keys
        return response()->json(array_filter([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ]), $code);
    }

    /**
     * Build an error JSON response.
     */
    protected function errorResponse(string $message, int $code): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}