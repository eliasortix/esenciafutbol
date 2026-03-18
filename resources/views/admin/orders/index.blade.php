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

        .page-wrap { max-width: 1440px; margin: 0 auto; padding: 40px 20px; }
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

        .top-stats { display: flex; gap: 16px; margin-bottom: 30px; }
        .stat-card { flex: 1; max-width: 280px; background: linear-gradient(145deg, #0f172a, #1e293b); border: 1px solid #1f2937; border-radius: var(--radius); padding: 24px; }
        .stat-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
        .stat-value { font-size: 32px; font-weight: 900; color: white; }

        .main-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--line); }
        .products-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .products-table thead th { background: #f9fafb; color: var(--muted); font-size: 11px; font-weight: 900; text-transform: uppercase; padding: 18px; border-bottom: 1px solid var(--line); text-align: left; }
        .products-table tbody td { padding: 20px 18px; border-bottom: 1px solid #f1f5f9; color: var(--text); font-size: 14px; font-weight: 600; }
        .status-badge { background: #f3f4f6; color: #374151; padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .status-badge.completado { background: #d1fae5; color: #065f46; }
    </style>

    <div class="page-wrap">
        <div class="page-header">
            <div class="flex items-center gap-4">
                <a href="{{ route('products.list') }}" class="btn-back-home">
                    <svg style="width:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver
                </a>
                <div class="header-title">
                    <h2>Registro de Pedidos</h2>
                    <p>Gestión de compras y stock con proveedores</p>
                </div>
            </div>

            <a href="{{ route('orders.create') }}" class="btn-create-sale">
                <span>+</span> Registrar Pedido
            </a>
        </div>

        <div class="top-stats">
            <div class="stat-card">
                <div class="stat-label">Total Pedidos</div>
                <div class="stat-value">{{ $orders->count() }}</div>
            </div>
            <div class="stat-card" style="border-left: 4px solid var(--accent);">
                <div class="stat-label">Inversión Total</div>
                <div class="stat-value">{{ number_format($orders->sum('total_amount'), 2) }}€</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pendientes</div>
                <div class="stat-value" style="color: #fbbf24;">{{ $orders->where('status', 'pendiente')->count() }}</div>
            </div>
        </div>

        <div class="main-card">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Total Coste</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td style="color: var(--muted); font-size: 12px;">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="color: var(--text); font-weight: 800;">{{ $order->product_name_snapshot }}</div>
                            <div style="font-size: 10px; color: var(--muted);">ID: #{{ $order->id }}</div>
                        </td>
                        <td>
                            <span class="status-badge {{ $order->status == 'completado' ? 'completado' : '' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 900; font-size: 16px;">
                            {{ number_format($order->total_amount, 2) }}€
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 100px; color: var(--muted);">
                            No hay pedidos registrados en el historial.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>