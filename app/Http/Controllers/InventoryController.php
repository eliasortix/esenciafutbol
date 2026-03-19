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
        $products = Product::orderBy('name')->get();

        // 1. Productos para la tabla (los que no están vendidos)
        $entries = Inventory::with('product')
            ->where('is_sold', false) 
            ->latest()
            ->get();
            
        $sales = Sale::latest()->get();
        
        // 2. Colecciones para cálculos y para el SELECT del formulario
        $availableStock = Inventory::with('product')->where('is_sold', false)->get(); // <--- REINSTALADA
        $soldItems = Inventory::where('is_sold', true)->get();

        // 3. Cálculos de las Cards
        $totalStock = $availableStock->count();
        $totalProfit = $sales->sum('company_profit');
        $currentInvestment = $availableStock->sum('cost_price');
        $recoveredInvestment = $soldItems->sum('cost_price');
        
        // 4. Comisiones
        $commissionsBySeller = Sale::where('seller_name', '!=', 'Web')
            ->selectRaw('seller_name, SUM(sale_price) as total_ventas, SUM(seller_commission) as total_comm')
            ->groupBy('seller_name')
            ->get();

        // 5. Enviamos TODO a la vista
        return view('inventory.index', compact(
            'products',
            'entries', 
            'sales', 
            'availableStock', // <--- Importante para el formulario de venta
            'totalStock', 
            'totalProfit',
            'currentInvestment',
            'recoveredInvestment',
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
        $nameForInventory = $validated['manual_product_name'] ?? 
                            ($productId ? Product::find($productId)->name : 'Producto Manual');

        for ($i = 0; $i < $validated['quantity']; $i++) {
            Inventory::create([
                'product_id' => $productId,
                'cost_price' => $validated['cost_price'],
                'size' => $validated['size'],
                'supplier_product_name' => $validated['supplier_product_name'] ?? $nameForInventory,
                'is_sold' => false,
            ]);
        }

        return redirect()->route('inventory.index')->with('success', "Stock actualizado.");
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Eliminado correctamente.');
    }
}