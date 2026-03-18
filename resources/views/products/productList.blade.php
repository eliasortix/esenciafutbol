<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Inventario de Productos') }}
        </h2>
    </x-slot>

    <div class="container-fluid px-4 py-4">
        <style>
            :root {
                --bg: #0b0f19;
                --card-dark: #111827;
                --card: #ffffff;
                --line: #e5e7eb;
                --text: #111827;
                --muted: #6b7280;
                --accent: #2563eb;
                --success-bg: #dcfce7;
                --success-text: #15803d;
                --shadow: 0 12px 34px rgba(0,0,0,.25);
                --radius: 18px;
            }

            .page-wrap { max-width: 1440px; margin: 0 auto; }
            .page-header { display: flex; justify-content: flex-end; align-items: center; gap: 16px; margin-bottom: 24px; margin-top: 20px; }
            
            .btn-sales { 
                display: inline-flex; align-items: center; gap: 10px; 
                background: white; color: var(--text); border: 1px solid var(--line);
                border-radius: 14px; padding: 12px 18px; font-weight: 700; 
                text-decoration: none; transition: all 0.2s;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            }
            .btn-sales:hover { background: #f9fafb; border-color: var(--accent); color: var(--accent); }

            /* Estilo específico para el botón de Pedidos */
            .btn-orders {
                border-color: #10b981 !important;
                color: #059669 !important;
            }
            .btn-orders:hover {
                background: #ecfdf5 !important;
            }

            .btn-create { 
                display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg,var(--accent),#3b82f6); 
                color: white; border-radius: 14px; padding: 12px 18px; font-weight: 700; text-decoration: none; box-shadow: 0 10px 20px rgba(37,99,235,.3); 
            }

            .top-stats { display: flex; gap: 16px; margin-bottom: 22px; }
            .stat-card { width: 220px; background: linear-gradient(145deg,#0f172a,#1e293b); border: 1px solid #1f2937; border-radius: var(--radius); padding: 20px; }
            .stat-label { font-size: 13px; color: #94a3b8; margin-bottom: 8px; }
            .stat-value { font-size: 28px; font-weight: 800; color: white; }
            
            .main-card { background: white; border-radius: 22px; box-shadow: var(--shadow); overflow: hidden; }
            .products-table { width: 100%; border-collapse: separate; border-spacing: 0; }
            .products-table thead th { background: #f9fafb; color: var(--muted); font-size: 12px; font-weight: 800; text-transform: uppercase; padding: 16px 18px; border-bottom: 1px solid var(--line); }
            .products-table tbody td { padding: 18px; border-bottom: 1px solid #f1f5f9; color: var(--text); font-size: 14px; }
            .product-cell { display: flex; align-items: center; gap: 14px; }
            .product-thumb { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 1px solid var(--line); }
            .product-name { font-weight: 800; margin: 0; font-size: 14px; }
            .supplier-name { font-size: 12px; color: #6366f1; font-weight: 600; }
            .id-badge { background: #eef2ff; color: #4338ca; padding: 4px 8px; border-radius: 6px; font-weight: 700; }
            .status-active { background: var(--success-bg); color: var(--success-text); padding: 5px 10px; border-radius: 999px; font-size: 12px; }
            .status-inactive { background: #fee2e2; color: #991b1b; padding: 5px 10px; border-radius: 999px; font-size: 12px; }
            .price { font-weight: 800; color: var(--text); }
            .actions { display: flex; gap: 8px; justify-content: flex-end; }
            .action-btn { border: 1px solid var(--line); padding: 6px 12px; border-radius: 10px; font-size: 13px; text-decoration: none; color: var(--text); font-weight: 600; }
        </style>

        <div class="page-wrap">
            <div class="page-header">
                <a href="{{ route('inventory.index') }}" class="btn-sales btn-orders">
                    <span>📦</span> Gestión de Productos
                </a>

                <a href="{{ route('products.create') }}" class="btn-create">
                    <span>＋</span> Nuevo producto
                </a>
            </div>

            <div class="top-stats">
                <div class="stat-card">
                    <div class="stat-label">Modelos Catálogo</div>
                    <div class="stat-value">{{ $products->total() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Stock Real (Pedidos)</div>
                    <div class="stat-value" style="color: #10b981;">{{ $availableStockCount ?? 0 }}</div>
                </div>
                <div class="stat-card" style="border-color: #15803d; width: 280px;">
                    <div class="stat-label" style="color: #4ade80;">Beneficio Estimado</div>
                    <div class="stat-value" style="color: #4ade80;">{{ number_format($salesTotal ?? 0, 2) }}€</div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-4" style="background: #dcfce7; color: #15803d; padding: 15px; border-radius: 12px; font-weight: 700;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="main-card">
                <div class="table-responsive">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>ID</th>
                                <th>Referencia Proveedor</th>
                                <th>Sección</th>
                                <th>Versión</th>
                                <th>Estado</th>
                                <th>Coste Base</th>
                                <th style="text-align: right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            @if ($product->images && $product->images->count())
                                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="product-thumb">
                                            @else
                                                <div class="product-thumb" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #9ca3af;">No img</div>
                                            @endif
                                            <div>
                                                <p class="product-name">{{ $product->name }}</p>
                                                <small class="text-muted">{{ $product->season }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="id-badge">#{{ $product->id }}</span></td>
                                    <td>
                                        <span class="supplier-name">{{ $product->supplier_product_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $product->section_type }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="padding: 4px 8px; border-radius: 6px; font-size: 11px;">
                                            {{ $product->version_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                            {{ $product->status === 'active' ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td><span class="price">{{ number_format($product->cost, 2) }}€</span></td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('products.edit', $product) }}" class="action-btn">✏️</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Borrar este modelo del catálogo?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-btn" style="color: #ef4444;">🗑</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-10" style="color: var(--muted);">No hay productos en el catálogo.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination-wrap mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>