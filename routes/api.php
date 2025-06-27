<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Information and Health Check
Route::get('/info', [ApiController::class, 'info']);
Route::get('/health', [ApiController::class, 'health']);

// Grupo de rutas con prefijo v1
Route::prefix('v1')->group(function () {
    // Users API Routes
    Route::apiResource('users', UserController::class);

    // Ejemplo de rutas adicionales
    Route::get('/example', function () {
        return response()->json([
            'message' => 'This is an example API endpoint',
            'version' => 'v1'
        ]);
    });
});
