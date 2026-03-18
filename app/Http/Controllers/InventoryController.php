<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // 1. Traemos los productos del catálogo (ESTO ES LO QUE FALTABA)
        $products = Product::orderBy('name')->get();

        // 2. Datos para las tablas
        $entries = Inventory::with('product')->latest()->get();
        $sales = Sale::latest()->get();
        $availableStock = Inventory::with('product')->where('is_sold', false)->get();

        // 3. Cálculos para las cards superiores
        $totalStock = $availableStock->count();
        $totalProfit = $sales->sum('company_profit');
        
        // 4. Sumar comisiones por vendedor
        $commissionsBySeller = Sale::where('seller_name', '!=', 'Web')
            ->selectRaw('seller_name, SUM(sale_price) as total_ventas, SUM(seller_commission) as total_comm')
            ->groupBy('seller_name')
            ->get();

        // 5. IMPORTANTE: Añadir 'products' al compact
        return view('inventory.index', compact(
            'products', // <--- Asegúrate de que esta línea esté aquí
            'entries', 
            'sales', 
            'availableStock', 
            'totalStock', 
            'totalProfit',
            'commissionsBySeller'
        ));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'manual_product_name' => 'nullable|string|max:255',
            'supplier_product_name' => 'nullable|string|max:255',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $productId = $validated['product_id'] ?? null;
        $nameForInventory = $validated['manual_product_name'] ?? ($productId ? Product::find($productId)->name : 'Producto Manual');

        for ($i = 0; $i < $validated['quantity']; $i++) {
            Inventory::create([
                'product_id' => $productId,
                'cost_price' => $validated['cost_price'],
                'size' => $validated['size'],
                'supplier_product_name' => $validated['supplier_product_name'] ?? $nameForInventory,
                'is_sold' => false,
            ]);
        }

        return redirect()->route('inventory.index')->with('success', "¡Stock actualizado! Se han añadido {$validated['quantity']} unidades.");
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Pedido eliminado correctamente');
    }
}