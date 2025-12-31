<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\Admin\AgentCategoryController;
use App\Http\Controllers\Api\Admin\TicketManagementController;
use App\Http\Controllers\Api\GuestTicketController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'TicketFlow API is running'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/guest/tickets', [GuestTicketController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply']);

    Route::post('/tickets/{ticket}/rating', [RatingController::class, 'store'])
        ->middleware('role:customer');

    Route::middleware('role:admin')->group(function () {

        Route::post('/admin/agent-categories', [AgentCategoryController::class, 'store']);
        Route::delete('/admin/agent-categories/{agentCategory}', [AgentCategoryController::class, 'destroy']);

        Route::post('/admin/tickets/{ticket}/reassign', [TicketManagementController::class, 'reassign']);
        Route::post('/admin/tickets/{ticket}/close', [TicketManagementController::class, 'close']);
    });
});
