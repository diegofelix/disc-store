<?php

use App\Http\Controllers\Admin\DiscsController;
use Illuminate\Support\Facades\Route;

Route::get('discs', [DiscsController::class, 'index']);
Route::post('discs', [DiscsController::class, 'store']);
