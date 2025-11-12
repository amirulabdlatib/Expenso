<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Lookup\AccountLookupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Lookup\CategoryLookupController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('accounts', AccountController::class);
    Route::get('/accounts/{id}', [AccountController::class, 'show']);
    Route::get('/getActiveAccountCount', [AccountController::class, 'getActiveAccountCount']);
    Route::get('/lookup/accounts', AccountLookupController::class);


    Route::resource('categories', CategoryController::class);
    Route::get('/lookup/categories', CategoryLookupController::class);

    Route::resource('transactions', TransactionController::class);
});
