<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Return a success response
     */
    protected function successResponse($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Return an error response
     */
    protected function errorResponse(string $message = 'Error', int $status = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Get API information
     */
    public function info(): JsonResponse
    {
        return $this->successResponse([
            'api_version' => '1.0.0',
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
        ], 'API Information retrieved successfully');
    }

    /**
     * Health check endpoint
     */
    public function health(): JsonResponse
    {
        return $this->successResponse([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'uptime' => 'Running',
        ], 'API is healthy');
    }
}
