<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\RecurringTransactionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AiController;

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Core Data Resources
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('recurring-transactions', RecurringTransactionController::class);
    Route::apiResource('notifications', NotificationController::class)->only(['index', 'update', 'destroy']);

    // AI Intelligence Features
    Route::prefix('ai')->group(function () {
        Route::get('/analytics', [AiController::class, 'getAnalytics']);
        Route::post('/chat', [AiController::class, 'handleChat']);
        Route::post('/budget-planner', [AiController::class, 'generateBudget']);
    });
});
