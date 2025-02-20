<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Api\SalesController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/prod/create', function () {
    return view('fomularioTeste');
});


Route::get('/sale/create', function () {
    $products = Product::all(); // Buscar todos os produtos
    $users = User::all(); // Buscar todos os compradores (usuários)
    $success = ' sdsds'; // Buscar todos os compradores (usuários)
    return view('registroDeVenda', compact('products', 'users', 'success'));
});



Route::post('/sale/store', [SalesController::class, 'store']);

