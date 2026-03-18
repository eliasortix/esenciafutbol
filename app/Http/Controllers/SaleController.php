<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Guardar una nueva venta y actualizar el stock
     */
    public function store(Request $request)
{
    $request->validate([
        'inventory_id' => 'required',
        'sale_price'   => 'required|numeric',
        'seller_name'  => 'required',
    ]);

    $inventoryItem = Inventory::with('product')->findOrFail($request->inventory_id);

    $cost = $inventoryItem->cost_price;
    $salePrice = $request->sale_price;
    $profit = $salePrice - $cost;
    $commission = $request->filled('commission') ? (float)$request->commission : 0;
    $companyProfit = $profit - $commission;

    // Guardamos el nombre del producto en la columna que SÍ existe: supplier_product_name
    Sale::create([
        'product_id'            => $inventoryItem->product_id, 
        'supplier_product_name' => $request->filled('product_name_manual') 
                                   ? $request->product_name_manual 
                                   : ($inventoryItem->product->name . " (Talla " . $inventoryItem->size . ")"), 
        'cost_price'            => $cost,
        'sale_price'            => $salePrice,
        'seller_name'           => $request->seller_name,
        'seller_commission'     => $commission,
        'company_profit'        => $companyProfit,
    ]);

    $inventoryItem->update(['is_sold' => true]);

    return redirect()->to(url()->previous() . '#sales')->with('success', 'Venta registrada.');
}

    /**
     * Eliminar una venta y restaurar el producto al stock
     */
    public function destroy(Sale $sale)
    {
        // Usamos inventory_id que ahora sí se guarda correctamente al crear la venta
        if ($sale->inventory_id) {
            $inventoryEntry = Inventory::find($sale->inventory_id);
            if ($inventoryEntry) {
                $inventoryEntry->update(['is_sold' => false]);
            }
        }

        $sale->delete();

        return redirect()->to(url()->previous() . '#sales')->with('success', 'Venta eliminada y stock restaurado.');
    }
}