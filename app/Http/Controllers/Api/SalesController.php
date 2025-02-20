<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ProductSold;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Cadastrar venda e inserir produtos vendidos no banco.
     */
    public function index()
    {
        return response()->json(Product::get());
    }

    public function create()
    {
        return view('sales.form');
    }


    // public function store(Request $request)
    // {
    //     DB::beginTransaction(); // Inicia uma transação
    //     try {
    //         // Extrair todos os product_id dos itens para uma única consulta ao banco
    //         $productIds = array_column($request->items, 'product_id');

    //         // Buscar todos os produtos de uma vez
    //         $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

    //         // Inicializar o valor total
    //         $totalValue = 0;

    //         // Calcular o valor total com base nos produtos sem fazer múltiplas consultas ao banco
    //         foreach ($request->items as $item) {
    //             $product = $products->get($item['product_id']); // Buscar produto já carregado
    //             if ($product) {
    //                 $totalValue += $product->price * $item['quantity'];
    //             }
    //         }

    //         $sale = User::find($request->user_id)->sales()->create([
    //             'total_value' => $totalValue, // Valor calculado dos itens
    //             'sale_date' => Carbon::now()
    //         ]);

    //         // Adicionar os itens vendidos
    //         foreach ($request->items as $item) {
    //             $sale->items()->create([
    //                 'product_id' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'price' => $products->get($item['product_id'])->price,
    //             ]);
    //         }

    //         DB::commit(); // Confirma a transação
    //         return response()->json(['message' => 'Venda criada com sucesso!'], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Reverte a transação em caso de erro
    //         return response()->json(['error' => 'Erro ao criar venda: ' . $e->getMessage()], 500);
    //     }
    // }

    public function store(Request $request)
    {
        DB::beginTransaction(); // Inicia uma transação
        try {
            // Criar a venda com total_value inicial 0
            $sale = Sale::create([
                'user_id' => $request->user_id,
                'total_value' => 0, // Será atualizado depois
                'sale_date' => now(),
            ]);

            $totalValue = 0; // Inicializa o total da venda

            // Registrar o item vendido e calcular o total
            $product = Product::find($request->product_id);

            if (!$product) {
                return redirect('/sale/create')->with('error', 'Produto não encontrado.');
            }

            ProductSold::create([
                'sale_id' => $sale->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);

            // Calcular o total da venda
            $totalValue += $product->price * $request->quantity;

            // Atualizar o total_value na venda
            $sale->update(['total_value' => $totalValue]);

            DB::commit(); // Confirma a transação

            return redirect('/sale/create')->with('success', 'Venda registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return redirect('/sale/create')->with('error', 'Erro ao registrar venda: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $sale = Sale::with('items')->find($id);

        if (!$sale) {
            return response()->json(['message' => 'Venda não encontrada.'], 404);
        }
        return response()->json($sale);
    }


    public function update(Request $request, $saleId)
    {
        // Validação dos dados da requisição
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Encontrar a venda pelo ID
        $sale = Sale::find($saleId);

        if (!$sale) {
            return response()->json(['message' => 'Venda não encontrada.'], 404);
        }

        // Calcular o novo valor total
        $totalValue = 0;
        foreach ($validatedData['items'] as $item) {
            $product = Product::find($item['product_id']);
            $totalValue += $product->price * $item['quantity'];
        }

        // Atualizar os detalhes da venda
        $sale->user_id = $validatedData['user_id'];
        $sale->total_value = $totalValue;
        $sale->save();

        // Remover os itens antigos associados à venda
        $sale->items()->delete();

        // Inserir os novos itens vendidos
        foreach ($validatedData['items'] as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => Product::find($item['product_id'])->price,
            ]);
        }

        return response()->json([
            'message' => 'Venda e itens atualizados com sucesso.',
            'sale' => $sale->load('items')
        ], 200);
    }

    public function destroy($id)
    {
        // Encontra a venda pelo ID
        $sale = Sale::find($id);

        // Verifica se a venda existe
        if (!$sale) {
            return response()->json(['message' => 'Venda não encontrada.'], 404);
        }

        // Deleta os itens vendidos relacionados a esta venda
        $sale->items()->delete();

        // Deleta a venda
        $sale->delete();

        return response()->json(['message' => 'Venda e itens associados deletados com sucesso.'], 200);
    }
}


