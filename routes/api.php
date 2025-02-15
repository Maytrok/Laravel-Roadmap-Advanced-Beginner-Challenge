<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post("login", [LoginController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("logout", [LoginController::class, "logout"]);
    Route::get("/users/checkAuth", [LoginController::class, "checkAuth"]);
    Route::apiResource("users", UserController::class);
    Route::apiResource("clients", ClientController::class);
    Route::get("projects/{project}/tasks", [TaskController::class, "projectTasks"]);
    Route::apiResource("projects", ProjectController::class);
    Route::apiResource("tasks", TaskController::class)->except("index");
});
