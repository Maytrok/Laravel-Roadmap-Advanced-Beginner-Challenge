<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


Route::post("login", [LoginController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("logout", [LoginController::class, "logout"]);
});
