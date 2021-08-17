<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post("login", [LoginController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("logout", [LoginController::class, "logout"]);

    Route::apiResource("users", UserController::class);
    Route::apiResource("clients", ClientController::class);
});
