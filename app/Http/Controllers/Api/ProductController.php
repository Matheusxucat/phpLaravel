<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function getProductQuantity(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $totalQuantity = ProductSold::join('sales', 'products_sold.sale_id', '=', 'sales.id')
        ->whereBetween('sales.sale_date', [$startDate, $endDate])
        ->sum('products_sold.quantity');
    
        return response()->json([
            'total_quantity' => $totalQuantity,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

     

    public function index()
    {
        return response()->json(Product::get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(Product::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        return response()->json($product->update($request->only(['name','price'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return response()->json($product->delete());
    }
}
