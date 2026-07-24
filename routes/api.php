<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MenuController;

Route::middleware('auth:sanctum')->group(function () {
    // Current Authenticated User Details
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
            'message' => 'Operation successful'
        ]);
    });

    // API Resources
    Route::apiResource('users', UserController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('offers', OfferController::class);
    Route::apiResource('sections', SectionController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('tables', TableController::class);
    Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('orders', OrderController::class);

    // Cashier Endpoints
    Route::get('/cashier', [CashierController::class, 'index']);
    Route::post('/cashier/orders', [CashierController::class, 'store']);

    // Menu Endpoint
    Route::get('/menu', [MenuController::class, 'index']);

    // Settings Endpoints
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);
});
