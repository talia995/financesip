<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('accounts.index');
});

Route::resource('accounts', AccountController::class);
Route::resource('categories', CategoryController::class);
Route::resource('transactions', TransactionController::class);
