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
        // Incluimos las nuevas columnas de personalización en la vista
        $entries = Inventory::with('product')
            ->where('is_sold', false) 
            ->latest()
            ->get();
            
        $sales = Sale::latest()->get();
        
        // 2. Colecciones para cálculos
        $availableStock = Inventory::with('product')->where('is_sold', false)->get();
        $soldItems = Inventory::where('is_sold', true)->get();

        // 3. Cálculos de las Cards (USANDO EL COSTE TOTAL COMPUTADO)
        $totalStock = $availableStock->count();
        $totalProfit = $sales->sum('company_profit');
        
        // IMPORTANTE: Ahora sumamos total_computed_cost que incluye los parches/dorsal
        $currentInvestment = $availableStock->sum('total_computed_cost');
        $recoveredInvestment = $soldItems->sum('total_computed_cost');
        
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
            'availableStock',
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
        'patches_qty' => 'required|integer|min:0|max:2',
        'patches_description' => 'nullable|string|max:255', // Nuevo
        'has_dorsal' => 'nullable',
        'dorsal_name' => 'nullable|string|max:255', // Nuevo
        'dorsal_number' => 'nullable|string|max:20', // Nuevo
    ]);

    $productId = $validated['product_id'] ?? null;
    $nameForInventory = $validated['manual_product_name'] ?? 
                        ($productId ? Product::find($productId)->name : 'Producto Manual');

    // Lógica de costes
    $patchPrice = 0.86;
    $dorsalPrice = 1.71;
    $extraPatches = $validated['patches_qty'] * $patchPrice;
    $extraDorsal = $request->has('has_dorsal') ? $dorsalPrice : 0;
    
    $totalComputed = $validated['cost_price'] + $extraPatches + $extraDorsal;

    for ($i = 0; $i < $validated['quantity']; $i++) {
        Inventory::create([
            'product_id' => $productId,
            'cost_price' => $validated['cost_price'],
            'patches_qty' => $validated['patches_qty'],
            'patches_description' => $validated['patches_description'], // Guardar texto
            'has_dorsal' => $request->has('has_dorsal'),
            'dorsal_name' => $validated['dorsal_name'], // Guardar nombre dorsal
            'dorsal_number' => $validated['dorsal_number'], // Guardar número dorsal
            'total_computed_cost' => $totalComputed,
            'size' => $validated['size'],
            'supplier_product_name' => $validated['supplier_product_name'] ?? $nameForInventory,
            'is_sold' => false,
        ]);
    }

    return redirect()->route('inventory.index')->with('success', "Stock añadido correctamente.");
}

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Registro eliminado correctamente.');
    }
}