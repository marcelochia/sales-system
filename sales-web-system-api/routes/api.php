<?php

use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sellers'], function () {
    Route::post('', [SellerController::class, 'store']);
    Route::get('', [SellerController::class, 'index']);
    Route::get('{id}', [SellerController::class, 'show']);
    Route::get('{id}/sales', [SellerController::class, 'sales']);
    Route::put('{id}', [SellerController::class, 'update']);
    Route::delete('{id}', [SellerController::class, 'destroy']);
});

Route::group(['prefix' => 'sales'], function () {
    Route::post('', [SaleController::class, 'store']);
    Route::get('', [SaleController::class, 'index']);
    Route::get('{id}', [SaleController::class, 'show']);
    Route::delete('{id}', [SaleController::class, 'destroy']);
});
