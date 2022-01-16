<?php

use App\Http\Controllers\Admin\DiscsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('discs', [DiscsController::class, 'index']);
