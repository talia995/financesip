<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('accounts.index');
});

Route::resource('accounts', AccountController::class);
