<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Muestra la lista de pedidos (LA TABLA)
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Muestra el formulario para registrar un nuevo pedido
     */
    public function create()
    {
        // Buscamos todos los productos del catálogo para el desplegable
        $products = \App\Models\Product::orderBy('name', 'asc')->get();
        
        // IMPORTANTE: Pasarlos con compact
        return view('admin.orders.create', compact('products'));
    }

    /**
     * Guarda el pedido y genera el stock con TALLAS
     */
    public function store(Request $request) 
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'manual_product_name' => 'nullable|string|max:255',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'supplier_product_name' => 'nullable|string|max:255',
        ]);

        if ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
            $finalName = $product->name;
            $productId = $product->id;
        } else {
            $finalName = $request->manual_product_name ?? 'Producto Manual';
            $productId = null; 
        }

        // 1. Crear el registro del Pedido (Historial)
        // Ajustamos los nombres de las columnas a lo que SQL acepta
        Order::create([
            'product_id'            => $productId,
            'product_name'          => $finalName . " - Talla " . $request->size,
            'cost_price'            => $request->cost_price, 
            'supplier_product_name' => $request->supplier_product_name ?? $finalName,
            'status'                => 'completado',
            'email'                 => 'sistema@esenciafutbol.com', 
            'is_available'          => true,
        ]);

        // 2. Crear las unidades reales en Inventario
        for ($i = 0; $i < $request->quantity; $i++) {
            Inventory::create([
                'product_id' => $productId,
                'size' => $request->size,
                'cost_price' => $request->cost_price,
                'supplier_product_name' => $request->supplier_product_name ?? $finalName,
                'is_sold' => false,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Pedido y stock registrados correctamente.');
    }
}