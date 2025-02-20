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

    public function getInvoicedProducts(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $productDetails = ProductSold::join('products', 'products_sold.product_id', '=', 'products.id')
            ->join('sales', 'products_sold.sale_id', '=', 'sales.id')
            ->select(
                'products.id',
                'products.name',
                //o sum ta calculando o valor total de cada consukta pela multiplicacao
                // e tras o total
                ProductSold::raw('SUM(products_sold.quantity) as quantidade'),
                ProductSold::raw('SUM(products_sold.quantity * products_sold.price) as totalValue')
            )

            //o where.... e pra agrupar essa consulta somente nas datas passadas na query
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            // o groupBy e para agrupar o resultado do id do produto com o nome
            ->groupBy('products.id', 'products.name')
            ->orderBy('quantidade', 'desc')
            ->get();

        $totalBilled = $productDetails->sum('totalValue');

        // return response()->json([
        //     'start_date' => $startDate,
        //     'end_date' => $endDate,
        //     'productdetails' => $productDetails,
        //     'total_billed' => $totalBilled,

        //  ]);
        return view('teste', ['productDetails' => $productDetails ]);
    }


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
        return view('fomularioTeste');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|string',
            ]);

            // Converter valor para o formato correto (substituir vírgula por ponto)
            $validatedData['price'] = str_replace(',', '.', $validatedData['price']);

            Product::create($validatedData);

            return redirect()->back()->with('success', 'Produto cadastrado com sucesso!');
        }
        //return response()->json(Product::create($request->all()));
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
        return response()->json($product->update($request->only(['name', 'price'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return response()->json($product->delete());
    }
}
