<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Season;
use App\Models\Supplier;
use App\Models\Team;
use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    /**
     * VISTA PRINCIPAL: LISTADO DE PRODUCTOS (INVENTARIO)
     */
    public function productList()
    {
        $products = Product::with(['team', 'seasonModel', 'supplier', 'images'])
            ->latest()
            ->paginate(15);

        $salesTotal = Sale::sum('company_profit');

        $availableStockCount = 0;
        if (Schema::hasTable('inventories')) {
            $availableStockCount = Inventory::where('is_sold', false)->count();
        }

        return view('products.productList', compact('products', 'salesTotal', 'availableStockCount'));
    }

    /**
     * VISTA CREAR PRODUCTO (CATÁLOGO)
     */
    public function productCreate()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $competitions = Competition::where('active', true)->orderBy('name')->get();
        $teams = Team::where('active', true)->orderBy('name')->get();
        $seasons = Season::where('active', true)->orderByDesc('sort_order')->get();

        return view('products.productCreate', compact('suppliers', 'competitions', 'teams', 'seasons'));
    }

    /**
     * GUARDAR PRODUCTO NUEVO EN EL CATÁLOGO
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
        // Probamos a guardar solo lo que el modelo decía originalmente
        Order::create([
            // Si 'product_id' te sigue dando error, comenta la línea de abajo
            // 'product_id' => $productId, 
            'product_name' => $finalName . " - Talla " . $request->size,
            'supplier_product_name' => $request->supplier_product_name ?? $finalName,
            'cost_price' => $request->cost_price,
            'is_available' => true,
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

    /**
     * VISTA EDITAR PRODUCTO
     */
    public function productEdit(Product $product)
    {
        $product->load('images', 'team', 'seasonModel');
        $suppliers = Supplier::orderBy('name')->get();
        $competitions = Competition::where('active', true)->orderBy('name')->get();
        $teams = Team::where('active', true)->orderBy('name')->get();
        $seasons = Season::where('active', true)->orderByDesc('sort_order')->get();

        return view('products.productEdit', compact('product', 'suppliers', 'competitions', 'teams', 'seasons'));
    }

    /**
     * ACTUALIZAR PRODUCTO
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'team_id' => 'required|exists:teams,id',
            'season_id' => 'required_unless:version_type,retro|nullable|exists:seasons,id',
            'season_manual' => 'required_if:version_type,retro|nullable|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive',
            'kit_type' => 'nullable|in:home,away,third,special',
            'version_type' => 'required|in:fan,player,retro',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_product_name' => 'nullable|string|max:255',
        ]);

        $seasonName = ($validated['version_type'] === 'retro') 
            ? ($validated['season_manual'] ?? 'Retro')
            : (Season::find($validated['season_id'])->name ?? 'S/T');

        $data = $validated;
        $data['season'] = $seasonName;
        
        // CORRECCIONES PARA ACTUALIZACIÓN
        $data['section_type'] = $request->section_type ?? $product->section_type ?? 'hombre';
        $data['price_type_id'] = $request->price_type_id ?? $product->price_type_id ?? 1;

        $data['cost'] = ['fan' => 28.0, 'player' => 30.0, 'retro' => 30.0][$validated['version_type']] ?? 30.00;

        unset($data['competition_id']);
        $product->update($data);

        if ($request->hasFile('images')) {
            $lastPos = $product->images()->max('position') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path' => $path,
                    'position' => $lastPos + $index + 1,
                    'alt_text' => $product->name,
                ]);
            }
        }

        return redirect()->route('products.list')->with('success', 'Producto actualizado.');
    }

    /**
     * ELIMINAR PRODUCTO
     */
    public function destroy(Product $product)
    {
        foreach($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        $product->delete();
        return redirect()->route('products.list')->with('success', 'Producto eliminado.');
    }

    /**
     * ELIMINAR IMAGEN ESPECÍFICA
     */
    public function destroyImage(ProductImage $image)
    {
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
        return back()->with('success', 'Imagen eliminada.');
    }

    /**
     * VISTA PÚBLICA / CATÁLOGO
     */
    public function catalog(Request $request)
    {
        $query = Product::with(['images', 'team.competition'])
            ->where('status', 'active');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('competition')) {
            $query->whereHas('team', function($q) use ($request) {
                $q->where('competition_id', $request->competition);
            });
        }

        if ($request->filled('team')) {
            $query->where('team_id', $request->team);
        }

        $products = $query->latest()->paginate(12);
        $competitions = Competition::where('active', true)->orderBy('name')->get();

        $teams = collect();
        if ($request->filled('competition')) {
            $teams = Team::where('competition_id', $request->competition)
                         ->where('active', true)
                         ->orderBy('name')
                         ->get();
        }

        return view('layouts.catalog', compact('products', 'competitions', 'teams'));
    }

    /**
     * API PARA AJAX (Equipos por competición)
     */
    public function getTeamsByCompetition($competitionId)
    {
        $teams = Team::where('competition_id', $competitionId)
                    ->where('active', true)
                    ->orderBy('name')
                    ->get(['id', 'name']);

        return response()->json($teams);
    }
}