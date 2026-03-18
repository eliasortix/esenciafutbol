<style>
    :root {
        --card: #ffffff;
        --line: #e5e7eb;
        --text: #111827;
        --muted: #6b7280;
        --accent: #2563eb;
        --radius: 22px;
    }

    /* Reducción de espacios para eliminar scroll */
    .order-header { background: #f9fafb; padding: 16px; border-bottom: 1px solid var(--line); text-align: center; }
    .order-header h3 { font-weight: 800; font-size: 14px; color: var(--text); text-transform: uppercase; letter-spacing: 1.5px; margin: 0; }
    .order-header p { color: var(--muted); font-size: 10px; font-weight: 700; text-transform: uppercase; margin-top: 2px; }

    .order-form { padding: 18px 24px; }
    .group-label { font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; display: block; letter-spacing: 0.5px; }
    
    .custom-input { 
        width: 100%; 
        background: #f9fafb; 
        border: 1px solid var(--line); 
        border-radius: 12px; 
        padding: 10px 14px; 
        font-size: 13px; 
        font-weight: 600; 
        color: var(--text); 
        transition: all 0.2s;
    }
    .custom-input:focus { background: white; border-color: var(--accent); outline: none; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    
    .or-divider { text-align: center; color: var(--muted); font-size: 9px; font-weight: 800; text-transform: uppercase; margin: 12px 0; position: relative; }
    .or-divider::before, .or-divider::after { content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: var(--line); }
    .or-divider::before { left: 0; } .or-divider::after { right: 0; }

    .btn-submit-order { 
        width: 100%; 
        background: linear-gradient(135deg, var(--accent), #3b82f6); 
        color: white; 
        border-radius: 12px; 
        padding: 14px; 
        font-weight: 700; 
        border: none; 
        box-shadow: 0 8px 16px rgba(37,99,235,.2); 
        cursor: pointer; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px; 
        transition: all 0.2s; 
        text-transform: uppercase; 
        font-size: 12px;
        margin-top: 10px;
    }
    .btn-submit-order:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(37,99,235,.3); }
    .hidden { display: none !important; }
</style>

<div class="order-card">
    <div class="order-header">
        <h3>Entrada de Mercancía</h3>
        <p>Registro de Stock y Tallas</p>
    </div>

    <form action="{{ route('inventory.store') }}" method="POST" class="order-form">
        @csrf
        
        <div>
            <label class="group-label">Elegir del Catálogo</label>
            <select name="product_id" id="product_select_order" class="custom-input">
                <option value="">-- NO ESTÁ EN EL CATÁLOGO --</option>
                @isset($products)
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>

        <div class="or-divider">O crear nuevo</div>

        <div id="manual-fields-order">
            <div style="margin-bottom: 12px;">
                <label class="group-label">Nombre del Producto</label>
                <input type="text" name="manual_product_name" class="custom-input" placeholder="Ej: Camiseta Retro Brasil 1998">
            </div>
            <div style="margin-bottom: 12px;">
                <label class="group-label">Nombre del Proveedor (Chino)</label>
                <input type="text" name="supplier_product_name" class="custom-input" placeholder="Nombre en el albarán">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
            <div>
                <label class="group-label">Talla</label>
                <select name="size" class="custom-input" required>
                    <option value="" disabled selected>Elegir...</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="Única">Única</option>
                </select>
            </div>
            <div>
                <label class="group-label">Unidades</label>
                <input type="number" name="quantity" class="custom-input" value="1" required min="1">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label class="group-label">Coste Compra Unidad (€)</label>
            <input type="number" name="cost_price" step="0.01" class="custom-input" placeholder="0.00" required>
        </div>

        <button type="submit" class="btn-submit-order">
            ＋ Confirmar Entrada
        </button>
    </form>
</div>

<script>
    document.getElementById('product_select_order').addEventListener('change', function() {
        const manualFields = document.getElementById('manual-fields-order');
        const divider = document.querySelector('.or-divider');
        if (this.value) {
            manualFields.classList.add('hidden');
            divider.classList.add('hidden');
        } else {
            manualFields.classList.remove('hidden');
            divider.classList.remove('hidden');
        }
    });
</script>