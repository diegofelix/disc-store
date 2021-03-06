<?php

use App\Http\Controllers\Admin\DiscsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('discs', [DiscsController::class, 'index']);
Route::get('discs/{id}', [DiscsController::class, 'show']);
Route::post('discs', [DiscsController::class, 'store']);
Route::delete('discs/{id}', [DiscsController::class, 'destroy']);

Route::post('users', [UsersController::class, 'register']);
Route::put('users/{id}', [UsersController::class, 'update']);
Route::delete('users/{id}', [UsersController::class, 'cancel']);

Route::get('orders', [OrdersController::class, 'index']);
Route::post('orders', [OrdersController::class, 'store']);
