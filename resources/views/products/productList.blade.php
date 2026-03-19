<x-app-layout>
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

            nav .shrink-0.flex.items-center, 
            nav .hidden.space-x-8 { display: none !important; }

            .page-wrap { max-width: 1440px; margin: 0 auto; }
            
            /* Ajuste de la cabecera superior */
            .top-actions-row {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                margin-bottom: 20px;
                margin-top: 10px;
            }

            /* Contenedor central: Card + Buscador */
            .stats-search-container {
                display: flex;
                align-items: stretch;
                gap: 20px;
                margin-bottom: 25px;
            }

            .search-wrapper {
                position: relative;
                flex-grow: 1;
                display: flex;
                align-items: center;
            }

            .search-input {
                width: 100%;
                height: 54px; /* Ajustado para que combine con la altura visual de la card */
                padding: 12px 18px 12px 45px;
                border-radius: 14px;
                border: 1px solid var(--line);
                font-size: 15px;
                background-color: white;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
                transition: all 0.2s;
            }

            .search-input:focus {
                outline: none;
                border-color: var(--accent);
                box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            }

            .search-icon {
                position: absolute;
                left: 15px;
                color: var(--muted);
                font-size: 18px;
                pointer-events: none;
            }
            
            /* Botones superiores */
            .btn-sales { 
                display: inline-flex; align-items: center; gap: 8px; 
                background: white; color: #059669; border: 1px solid #10b981;
                border-radius: 12px; padding: 10px 16px; font-weight: 700; 
                text-decoration: none; transition: all 0.2s;
            }

            .btn-create { 
                display: inline-flex; align-items: center; gap: 8px; 
                background: #2563eb; color: white !important; 
                border-radius: 12px; padding: 10px 20px; 
                font-weight: 700; text-decoration: none;
                box-shadow: 0 4px 12px rgba(37,99,235,0.2);
            }

            /* Card de Modelo */
            .stat-card { 
                min-width: 240px; 
                background: #111827; 
                border-radius: var(--radius); 
                padding: 20px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .stat-label { font-size: 12px; color: #94a3b8; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
            .stat-value { font-size: 32px; font-weight: 800; color: white; line-height: 1; }
            
            .main-card { background: white; border-radius: 22px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--line); }
            .products-table { width: 100%; border-collapse: separate; border-spacing: 0; }
            .products-table thead th { background: #f9fafb; color: var(--muted); font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 16px 18px; border-bottom: 1px solid var(--line); }
            .products-table tbody td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; color: var(--text); font-size: 14px; }
            
            .product-cell { display: flex; align-items: center; gap: 12px; }
            .product-thumb { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; border: 1px solid var(--line); }
            .product-name { font-weight: 700; margin: 0; font-size: 14px; color: #1f2937; }
            .id-badge { background: #f3f4f6; color: #6b7280; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600; }
            
            .status-active { background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; }
            
            .price { font-weight: 700; color: #111827; }
            .action-btn { border: 1px solid var(--line); padding: 6px; border-radius: 8px; color: var(--muted); transition: all 0.2s; background: white; }
            .action-btn:hover { background: #f9fafb; color: var(--accent); }
        </style>

        <div class="page-wrap">
            <div class="top-actions-row">
                <a href="{{ route('inventory.index') }}" class="btn-sales">
                    <span>📦</span> Gestión de Productos
                </a>
                <a href="{{ route('products.create') }}" class="btn-create">
                    <span>＋</span> Nuevo producto
                </a>
            </div>

            <div class="stats-search-container">
                <div class="stat-card">
                    <div class="stat-label">Modelos Catálogo</div>
                    <div class="stat-value">{{ $products->total() }}</div>
                </div>

                <form action="{{ route('products.list') }}" method="GET" class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" class="search-input" placeholder="Buscar producto, referencia o ID..." value="{{ request('search') }}">
                </form>
            </div>

            @if (session('success'))
                <div class="alert mb-4" style="background: #dcfce7; color: #15803d; padding: 12px 16px; border-radius: 12px; font-weight: 600; border: 1px solid #bbf7d0;">
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
                                <th>Ref. Proveedor</th>
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
                                                <div class="product-thumb" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #9ca3af;">Sin foto</div>
                                            @endif
                                            <div>
                                                <p class="product-name">{{ $product->name }}</p>
                                                <small style="color: #9ca3af; font-size: 11px;">{{ $product->season }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="id-badge">#{{ $product->id }}</span></td>
                                    <td style="color: #6366f1; font-weight: 600;">{{ $product->supplier_product_name ?? 'N/A' }}</td>
                                    <td>{{ $product->section_type }}</td>
                                    <td>
                                        <span class="border" style="padding: 2px 6px; border-radius: 4px; font-size: 11px; background: #fff;">
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
                                        <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                            <a href="{{ route('products.edit', $product) }}" class="action-btn">✏️</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-btn" style="color: #ef4444;">🗑</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-12" style="color: #9ca3af;">
                                        No se encontraron resultados para su búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>