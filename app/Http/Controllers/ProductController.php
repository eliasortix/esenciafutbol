<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceType;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * LISTADO SIMPLE DE PRODUCTOS
     */
    public function productList()
    {
        $products = Product::with(['priceType', 'supplier'])
            ->latest()
            ->paginate(15);

        return view('products.productList', compact('products'));
    }

    /**
     * VISTA CREAR PRODUCTO
     */
    public function productCreate()
    {
        $priceTypes = PriceType::where('active', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('products.productCreate', compact('priceTypes', 'suppliers'));
    }

    /**
     * GUARDAR PRODUCTO NUEVO
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'section_type' => 'required|in:league,national_team,retro',
            'season' => 'nullable|string|max:50',
            'kit_type' => 'nullable|in:home,away,third,special',
            'version_type' => 'required|in:fan,player',
            'price_type_id' => 'required|exists:price_types,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);

        return redirect()
            ->route('products.list')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * VISTA EDITAR PRODUCTO
     */
    public function productEdit(Product $product)
    {
        $priceTypes = PriceType::where('active', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('products.productEdit', compact('product', 'priceTypes', 'suppliers'));
    }

    /**
     * ACTUALIZAR PRODUCTO
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'section_type' => 'required|in:league,national_team,retro',
            'season' => 'nullable|string|max:50',
            'kit_type' => 'nullable|in:home,away,third,special',
            'version_type' => 'required|in:fan,player',
            'price_type_id' => 'required|exists:price_types,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        return redirect()
            ->route('products.list')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * BORRAR PRODUCTO
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.list')
            ->with('success', 'Producto eliminado correctamente.');
    }
}