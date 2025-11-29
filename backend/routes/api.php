<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Lookup\AccountLookupController;
use App\Http\Controllers\Lookup\CategoryLookupController;
use App\Http\Controllers\Lookup\ParentCategoryLookupController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('accounts', AccountController::class);
    Route::get('/accounts/{id}', [AccountController::class, 'show']);
    Route::get('/getActiveAccountCount', [AccountController::class, 'getActiveAccountCount']);
    Route::get('/lookup/accounts', AccountLookupController::class);


    Route::resource('categories', CategoryController::class)->except(['create']);
    Route::get('/lookup/categories', CategoryLookupController::class);
    Route::get('/lookup/parent/categories', ParentCategoryLookupController::class);

    Route::resource('transactions', TransactionController::class)->except(['create']);
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'getReceipt']);
    Route::get('/transactions/{transaction}/receipt-info', [TransactionController::class, 'getReceiptInfo']);

    Route::apiResource('budgets', BudgetController::class);
    Route::get('/budgets/{budget}/edit', [BudgetController::class, 'edit']);

    Route::get('profile', [ProfileController::class, 'index']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::delete('profile', [ProfileController::class, 'destroy']);
});
