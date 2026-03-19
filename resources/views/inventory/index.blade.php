<x-app-layout>
    <style>
        :root {
            --bg: #f3f4f6;
            --card: #ffffff;
            --line: #e5e7eb;
            --text: #111827;
            --muted: #6b7280;
            --accent: #2563eb;
            --shadow: 0 12px 34px rgba(0,0,0,.15);
            --radius: 22px;
        }

        .page-wrap { max-width: 1440px; margin: 0 auto; padding: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .header-title h2 { font-size: 22px; font-weight: 900; color: var(--text); text-transform: uppercase; margin: 0; }
        
        /* Contenedor de Acciones (Tabs + Buscador) */
        .actions-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 20px; }

        /* Tabs Estilo iOS */
        .tabs-container { display: flex; gap: 8px; background: #e5e7eb; padding: 5px; border-radius: 16px; width: fit-content; }
        .tab-btn { padding: 10px 20px; border-radius: 12px; border: none; font-weight: 800; font-size: 11px; text-transform: uppercase; cursor: pointer; transition: all 0.3s; color: var(--muted); background: transparent; }
        .tab-btn.active { background: white; color: var(--accent); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

        .btn-action { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 14px; font-weight: 800; text-decoration: none; font-size: 12px; text-transform: uppercase; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), #3b82f6); color: white; box-shadow: 0 10px 20px rgba(37,99,235,.3); }
        .btn-back { background: white; color: var(--text); border: 1px solid var(--line); }

        /* Buscador Compacto */
        .search-wrapper { position: relative; display: flex; align-items: center; }
        .search-input { background: white; border: 1px solid var(--line); padding: 10px 15px 10px 40px; border-radius: 14px; font-size: 13px; width: 260px; font-weight: 600; transition: all 0.3s; }
        .search-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); width: 300px; }
        .search-icon { position: absolute; left: 15px; color: var(--muted); }

        /* Estilos de Tabla */
        .main-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--line); }
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table thead th { background: #f9fafb; color: var(--muted); font-size: 11px; font-weight: 900; text-transform: uppercase; padding: 18px; text-align: left; border-bottom: 1px solid var(--line); }
        .custom-table tbody td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; color: var(--text); font-size: 14px; font-weight: 600; }
        
        .status-badge { padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-info { background: #eef2ff; color: #4338ca; }
        .badge-warning { background: #fef3c7; color: #92400e; }

        .btn-delete { color: #fca5a5; transition: 0.2s; background: none; border: none; cursor: pointer; padding: 5px; border-radius: 8px; }
        .btn-delete:hover { color: #ef4444; background: #fef2f2; }

        .btn-sell-row { background: #10b981; color: white; padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 800; border: none; cursor: pointer; text-transform: uppercase; transition: 0.2s; }
        .btn-sell-row:hover { background: #059669; transform: scale(1.05); }

        .hidden { display: none !important; }

        /* MODALES */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }
        .modal-content { position: relative; width: 100%; max-width: 500px; background: white; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: scale(0.95); transition: transform 0.3s ease; overflow: hidden; }
        .modal-overlay.show .modal-content { transform: scale(1); }
        .btn-close-modal { position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.05); border: none; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 100; font-size: 12px; color: var(--muted); }
    </style>

    <div class="page-wrap">
        {{-- Header Superior --}}
        <div class="page-header">
            <div class="flex items-center gap-4">
                <a href="{{ url('/products') }}" class="btn-action btn-back">← Catálogo</a>
                <div class="header-title"><h2>Logística e Inventario</h2></div>
            </div>
            
            {{-- Botón de imprimir solo para Cuentas --}}
            <div id="btn-container-accounts" class="hidden">
                <button class="btn-action btn-back" onclick="window.print()">🖨️ Imprimir Informe</button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
            <div style="background: #1e293b; padding: 25px; border-radius: 24px; color: white; box-shadow: var(--shadow);">
                <p style="color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Stock Real</p>
                <h3 style="font-size: 32px; font-weight: 900; margin: 0; color: #3b82f6;">{{ $totalStock }} <span style="font-size: 16px; font-weight: 500; color: #94a3b8;">u.</span></h3>
            </div>
            <div style="background: #1e293b; padding: 25px; border-radius: 24px; color: white; box-shadow: var(--shadow);">
                <p style="color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Inversión</p>
                <h3 style="font-size: 32px; font-weight: 900; margin: 0; color: #fca5a5;">{{ number_format($currentInvestment, 2) }}€</h3>
            </div>
            <div style="background: #1e293b; padding: 25px; border-radius: 24px; color: white; box-shadow: var(--shadow);">
                <p style="color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Recuperado</p>
                <h3 style="font-size: 32px; font-weight: 900; margin: 0; color: #60a5fa;">{{ number_format($recoveredInvestment, 2) }}€</h3>
            </div>
            <div style="background: #1e293b; padding: 25px; border-radius: 24px; color: white; box-shadow: var(--shadow);">
                <p style="color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Comisiones</p>
                <h3 style="font-size: 32px; font-weight: 900; margin: 0; color: #f59e0b;">{{ number_format($sales->sum('seller_commission'), 2) }}€</h3>
            </div>
            <div style="background: #1e293b; padding: 25px; border-radius: 24px; color: white; box-shadow: var(--shadow);">
                <p style="color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Bficio. Neto</p>
                <h3 style="font-size: 32px; font-weight: 900; margin: 0; color: #10b981;">{{ number_format($totalProfit, 2) }}€</h3>
            </div>
        </div>

        <div class="actions-bar">
            <div class="tabs-container">
                <button id="btn-tab-inventory" class="tab-btn active" onclick="switchTab('inventory')">📦 Inventario</button>
                <button id="btn-tab-sales" class="tab-btn" onclick="switchTab('sales')">💰 Ventas</button>
                <button id="btn-tab-accounts" class="tab-btn" onclick="switchTab('accounts')">👥 Cuentas</button>
            </div>

            <div id="btn-container-inventory" class="flex items-center gap-3">
                <div class="search-wrapper">
                    <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" id="inventorySearch" class="search-input" placeholder="Buscar producto, talla...">
                </div>
                <button onclick="openModal('order')" class="btn-action btn-primary">＋ Nuevo Pedido</button>
            </div>
        </div>

        {{-- TAB: INVENTARIO --}}
        <div id="tab-inventory" class="main-card">
            <table class="custom-table" id="inventoryTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto / Proveedor</th>
                        <th>Talla</th>
                        <th>Coste</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr>
                        <td style="color: var(--muted);">{{ $entry->created_at->format('d/m/y') }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $entry->product->name ?? 'Carga Manual' }}</div>
                            <div style="font-size: 10px; color: var(--muted); text-transform: uppercase;">{{ $entry->supplier_product_name ?? 'Sin proveedor' }}</div>
                        </td>
                        <td><span class="status-badge badge-info">{{ $entry->size }}</span></td>
                        <td>{{ number_format($entry->cost_price, 2) }}€</td>
                        <td><span class="status-badge {{ $entry->is_sold ? '' : 'badge-success' }}">{{ $entry->is_sold ? 'Vendido' : 'En Stock' }}</span></td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                @if(!$entry->is_sold)
                                    <button onclick="venderProducto({{ $entry->id }}, {{ $entry->cost_price }}, '{{ $entry->product->name ?? 'Producto' }} (Talla {{ $entry->size }})')" class="btn-sell-row">💰 Vender</button>
                                @endif
                                <form action="{{ route('inventory.destroy', $entry) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TAB: VENTAS --}}
        <div id="tab-sales" class="main-card hidden">
            <table class="custom-table" id="salesTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Vendedor</th>
                        <th>Venta</th>
                        <th>Comisión</th>
                        <th style="text-align: right;">Bficio. Empresa</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td style="color: var(--muted);">{{ $sale->created_at->format('d/m/y') }}</td>
                        <td><div style="font-weight: 700;">{{ $sale->supplier_product_name ?? 'S/N' }}</div></td>
                        <td><span class="status-badge badge-warning">{{ $sale->seller_name }}</span></td>
                        <td style="font-weight: 700;">{{ number_format($sale->sale_price, 2) }}€</td>
                        <td style="color: #f59e0b; font-weight: 700;">{{ number_format($sale->seller_commission, 2) }}€</td>
                        <td style="text-align: right; color: #10b981; font-weight: 800;">+{{ number_format($sale->company_profit, 2) }}€</td>
                        <td style="text-align: right;">
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('¿Eliminar venta?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TAB: CUENTAS --}}
        <div id="tab-accounts" class="hidden">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                @foreach($commissionsBySeller as $stat)
                <div class="main-card" style="padding: 20px; border-left: 6px solid var(--accent);">
                    <h4 style="margin: 0 0 15px 0; text-transform: uppercase; font-size: 14px;">{{ $stat->seller_name }}</h4>
                    <div style="display: flex; justify-content: space-between; background: #f9fafb; padding: 15px; border-radius: 12px;">
                        <div><p style="font-size: 10px; color: var(--muted); margin-bottom: 4px;">VOLUMEN</p><strong>{{ number_format($stat->total_ventas, 2) }}€</strong></div>
                        <div><p style="font-size: 10px; color: #10b981; margin-bottom: 4px;">COMISIÓN</p><strong style="color: #10b981; font-size: 18px;">{{ number_format($stat->total_comm, 2) }}€</strong></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODALES --}}
    <div id="order-form-overlay" class="modal-overlay"><div class="modal-content"><button onclick="closeModal('order')" class="btn-close-modal">✕</button>@include('inventory.create')</div></div>
    <div id="sale-form-overlay" class="modal-overlay"><div class="modal-content"><button onclick="closeModal('sale')" class="btn-close-modal">✕</button>@include('admin.sales.form')</div></div>

    <script>
        function switchTab(tab) {
            // Actualizar botones de pestañas
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-tab-' + tab).classList.add('active');

            // Ocultar todas las secciones y sus contenedores de botones
            ['inventory', 'sales', 'accounts'].forEach(t => {
                document.getElementById('tab-' + t).classList.add('hidden');
                let btnCont = document.getElementById('btn-container-' + t);
                if(btnCont) btnCont.classList.add('hidden');
            });

            // Mostrar la pestaña seleccionada y sus botones
            document.getElementById('tab-' + tab).classList.remove('hidden');
            let activeBtnCont = document.getElementById('btn-container-' + tab);
            if(activeBtnCont) activeBtnCont.classList.remove('hidden');
        }

        // Buscador reactivo (funciona para Inventario y Ventas)
        document.getElementById('inventorySearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            // Detectamos qué tabla está visible actualmente
            let targetTable = document.getElementById('tab-inventory').classList.contains('hidden') ? '#salesTable' : '#inventoryTable';
            let rows = document.querySelectorAll(targetTable + ' tbody tr');
            
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });

        function venderProducto(id, coste, nombre) {
            openModal('sale');
            if (typeof window.fillSaleForm === "function") {
                window.fillSaleForm(id, coste, nombre);
            }
        }

        function openModal(type) {
            document.getElementById(type + '-form-overlay').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(type) {
            document.getElementById(type + '-form-overlay').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                const type = event.target.id.split('-')[0];
                closeModal(type);
            }
        }
    </script>
</x-app-layout>