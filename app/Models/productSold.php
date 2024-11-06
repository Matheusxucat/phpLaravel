<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSold extends Model
{
    use HasFactory;

    protected $table = 'products_sold';
    // Permite o preenchimento dos campos de forma massiva
    protected $fillable = [
        'sale_id',    // ID da venda
        'product_id', // ID do produto
        'quantity',   // Quantidade vendida
        'price'       // Preço do produto
    ];
}




