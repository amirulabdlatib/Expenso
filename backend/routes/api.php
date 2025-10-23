<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('accounts', AccountController::class);
    Route::get('/getActiveAccountCount',[AccountController::class,'getActiveAccountCount']);

    Route::resource('categories',CategoryController::class);
});
