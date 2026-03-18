<x-app-layout>
    <style>
        /* VARIABLES Y ESTILOS GENERALES */
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

        .page-wrap { max-width: 1440px; margin: 0 auto; padding: 40px 20px; transition: all 0.3s ease; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-title h2 { font-size: 24px; font-weight: 900; color: var(--text); text-transform: uppercase; margin: 0; }
        .header-title p { color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; }

        .btn-back-home { 
            display: inline-flex; align-items: center; gap: 8px; background: white; color: var(--text); 
            border: 1px solid var(--line); border-radius: 14px; padding: 10px 16px; font-weight: 700; 
            text-decoration: none; font-size: 11px; text-transform: uppercase; box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .btn-create-sale { 
            display: inline-flex; align-items: center; gap: 10px; 
            background: linear-gradient(135deg, var(--accent), #3b82f6); 
            color: white; border-radius: 14px; padding: 14px 24px; 
            font-weight: 800; text-decoration: none; border: none;
            box-shadow: 0 10px 20px rgba(37,99,235,.3); cursor: pointer;
            text-transform: uppercase; font-size: 12px;
        }

        /* TABLA Y STATS */
        .top-stats { display: flex; gap: 16px; margin-bottom: 30px; }
        .stat-card { flex: 1; max-width: 280px; background: linear-gradient(145deg, #0f172a, #1e293b); border: 1px solid #1f2937; border-radius: var(--radius); padding: 24px; }
        .stat-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
        .stat-value { font-size: 32px; font-weight: 900; color: white; }

        .main-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--line); }
        .products-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .products-table thead th { background: #f9fafb; color: var(--muted); font-size: 10px; font-weight: 900; text-transform: uppercase; padding: 18px; border-bottom: 1px solid var(--line); text-align: left; }
        .products-table tbody td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; color: var(--text); font-size: 13px; font-weight: 600; }
        .vendedor-badge { background: #eef2ff; color: #4338ca; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; }

        .btn-delete { color: #fca5a5; background: none; border: none; cursor: pointer; padding: 5px; transition: 0.2s; }
        .btn-delete:hover { color: #ef4444; }

        /* MODO REGISTRO */
        .hidden { display: none !important; }
        body.creating-sale .main-content-area, body.creating-sale nav { display: none !important; }
        body.creating-sale #venta-form-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    </style>

    <div class="main-content-area page-wrap">
        <div class="page-header">
            <div class="flex items-center gap-4">
                <a href="{{ url('/products') }}" class="btn-back-home">
                    <svg width="16" height="16" viewBox="0 0 256 256"><path fill="currentColor" d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"></path></svg>
                    Inventario
                </a>
                <div class="header-title">
                    <h2>Registro de Ventas</h2>
                    <p>Historial y Control de Beneficios</p>
                </div>
            </div>
            <button onclick="showSaleForm()" class="btn-create-sale">
                <span>＋</span> Registrar Nueva Venta
            </button>
        </div>

        <div class="top-stats">
            <div class="stat-card">
                <div class="stat-label">Ventas Totales</div>
                <div class="stat-value">{{ $sales->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ingresos Brutos</div>
                <div class="stat-value">{{ number_format($sales->sum('sale_price'), 2) }}€</div>
            </div>
            <div class="stat-card" style="border-left: 4px solid #10b981;">
                <div class="stat-label">Beneficio Empresa Total</div>
                <div class="stat-value" style="color: #10b981;">
                    {{ number_format($sales->sum('company_profit'), 2) }}€
                </div>
            </div>
        </div>

        <div class="main-card">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Vendedor</th>
                        <th>Coste</th>
                        <th>Venta</th>
                        <th>Comisión</th>
                        <th style="color: var(--accent); background: #f0f7ff;">Total para Ti</th>
                        <th style="text-align: right;">Bficio. Empresa</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    @php
                        // Ajustado a tus nombres de DB reales: product_name_manual y seller_commission
                        $comision = $sale->seller_commission ?? 0;
                        $totalParaTi = $sale->cost_price + $comision;
                        $beneficioEmpresa = $sale->company_profit;
                    @endphp
                    <tr>
                        <td style="color: var(--muted); font-size: 11px;">{{ $sale->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight: 800;">{{ $sale->product_name_manual ?? 'Carga Manual' }}</div>
                        </td>
                        <td><span class="vendedor-badge">{{ $sale->seller_name }}</span></td>
                        
                        <td style="color: #94a3b8;">{{ number_format($sale->cost_price, 2) }}€</td>
                        
                        <td style="font-weight: 800;">{{ number_format($sale->sale_price, 2) }}€</td>
                        
                        <td style="color: #f59e0b; font-weight: 700;">{{ number_format($comision, 2) }}€</td>
                        
                        <td style="background: #f8fafc; color: var(--accent); font-weight: 900; border-left: 2px solid var(--accent);">
                            {{ number_format($totalParaTi, 2) }}€
                        </td>

                        <td style="text-align: right; color: #10b981; font-weight: 900;">
                            +{{ number_format($beneficioEmpresa, 2) }}€
                        </td>

                        <td>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('¿Borrar esta venta?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="venta-form-container" class="hidden">
        @include('admin.sales.form')
    </div>

    <script>
        window.onload = function() {
            if(window.location.hash === "#sales") {
                console.log("Cargado en vista de ventas");
            }
        };

        function showSaleForm() {
            document.body.classList.add('creating-sale');
            document.getElementById('venta-form-container').classList.remove('hidden');
            window.scrollTo(0, 0);
        }

        function hideSaleForm() {
            document.body.classList.remove('creating-sale');
            document.getElementById('venta-form-container').classList.add('hidden');
            window.location.hash = "sales";
        }
    </script>
</x-app-layout>