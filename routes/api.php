<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::apiResource('/users', UserController::class);
Route::apiResource('/product', ProductController::class);
Route::apiResource('/sale', SalesController::class);
