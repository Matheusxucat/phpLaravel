<?php


use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::apiResource('/users', UserController::class);
Route::apiResource('/product', ProductController::class);
Route::apiResource('/sale', SalesController::class);
Route::get('/productquantity', [ProductController::class, 'getProductQuantity']);
Route::get('/invoicedproducts', [ProductController::class, 'getInvoicedProducts']);
Route::get('/addProduct', [ProductController::class, 'create']);
Route::get('/sale/create', [SalesController::class, 'create'])->name('sale.create');
//GET http://127.0.0.1:8000/api/invoicedproducts?start_date=2025-01-01&end_date=2025-01-08
//http://127.0.0.1:8000/api/product
