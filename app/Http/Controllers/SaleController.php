<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;

class SaleController extends Controller
{
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

        // IMPORTANTE: He añadido inventory_id aquí abajo
        Sale::create([
            'inventory_id'          => $inventoryItem->id, // <--- ESTO FALTABA
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

        // Actualizamos el estado en el inventario
        $inventoryItem->update(['is_sold' => true]);

        return redirect()->to(url()->previous() . '#sales')->with('success', 'Venta registrada.');
    }

    public function destroy(Sale $sale)
    {
        // 1. Buscamos el producto asociado a esa venta
        // Usamos el inventory_id que guardamos en la tabla sales
        if ($sale->inventory_id) {
            $inventoryEntry = \App\Models\Inventory::find($sale->inventory_id);
            
            if ($inventoryEntry) {
                // 2. IMPORTANTE: Volvemos a ponerlo como NO VENDIDO
                $inventoryEntry->update(['is_sold' => false]);
            }
        }

        // 3. Ahora sí, borramos la venta
        $sale->delete();

        return redirect()->to(url()->previous() . '#sales')->with('success', 'Venta eliminada y producto devuelto al stock.');
    }
}