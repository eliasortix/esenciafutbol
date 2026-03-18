<x-app-layout>
    <style>
        :root { --accent: #2563eb; --radius: 22px; }
        .form-container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-card { background: white; border-radius: var(--radius); shadow: 0 15px 35px rgba(0,0,0,0.1); padding: 40px; border: 1px solid #e5e7eb; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #6b7280; margin-bottom: 8px; }
        .input-field { width: 100%; background: #f3f4f6; border: 2px solid transparent; border-radius: 14px; padding: 12px 16px; font-weight: 700; color: #111827; }
        .input-field:focus { border-color: var(--accent); background: white; outline: none; }
        .btn-submit { width: 100%; background: linear-gradient(135deg, var(--accent), #3b82f6); color: white; padding: 16px; border-radius: 14px; font-weight: 800; text-transform: uppercase; border: none; cursor: pointer; box-shadow: 0 10px 15px rgba(37,99,235,0.2); }
        .divider { text-align: center; margin: 20px 0; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; position: relative; }
        .divider::before { content: ""; position: absolute; top: 50%; left: 0; width: 40%; height: 1px; background: #e5e7eb; }
        .divider::after { content: ""; position: absolute; top: 50%; right: 0; width: 40%; height: 1px; background: #e5e7eb; }
    </style>

    <div class="form-container">
        <div class="form-card">
            <h2 style="font-size: 20px; font-weight: 900; text-transform: uppercase; margin-bottom: 5px;">Entrada de Stock</h2>
            <p style="color: #6b7280; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 30px;">Registro de Pedido y Tallas</p>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                
                <div class="input-group">
                    <label>Producto del Catálogo (Si ya existe)</label>
                    <select name="product_id" class="input-field">
                        <option value="">-- SELECCIONA SI YA ESTÁ EN EL CATÁLOGO --</option>
                        @forelse($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} {{ $product->sku ? '('.$product->sku.')' : '' }}
                            </option>
                        @empty
                            <option value="" disabled>No hay productos en el catálogo aún</option>
                        @endforelse
                    </select>
                </div>

                <div class="divider">O REGISTRA UNO NUEVO A MANO</div>

                <div class="input-group">
                    <label>Nombre del Producto Manual</label>
                    <input type="text" name="manual_product_name" class="input-field" placeholder="Ej: Camiseta Retro Milán 94">
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Talla</label>
                        <select name="size" class="input-field" required>
                            @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $talla)
                                <option value="{{ $talla }}" {{ $talla == 'M' ? 'selected' : '' }}>{{ $talla }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Cantidad</label>
                        <input type="number" name="quantity" class="input-field" value="1" min="1" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Coste por Unidad (€)</label>
                    <input type="number" name="cost_price" step="0.01" class="input-field" placeholder="0.00" required>
                </div>

                <button type="submit" class="btn-submit">Registrar e Incrementar Stock</button>
            </form>
        </div>
    </div>
</x-app-layout>