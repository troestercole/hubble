<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->middleware(['auth:sanctum', 'permission:users.create']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
});
