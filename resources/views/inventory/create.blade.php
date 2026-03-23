<style>
    :root {
        --card: #ffffff;
        --line: #e5e7eb;
        --text: #111827;
        --muted: #6b7280;
        --accent: #2563eb;
        --radius: 22px;
        --bg-extra: #f0f7ff;
    }

    /* Ajuste para que el modal no sea tan largo */
    .order-card { 
        max-height: 85vh; 
        display: flex; 
        flex-direction: column; 
    }
    
    .order-form { 
        padding: 18px 24px; 
        overflow-y: auto; /* Permite scroll si el contenido crece */
        flex-grow: 1;
    }

    .order-header { background: #f9fafb; padding: 16px; border-bottom: 1px solid var(--line); text-align: center; }
    .order-header h3 { font-weight: 800; font-size: 14px; color: var(--text); text-transform: uppercase; margin: 0; }
    .order-header p { color: var(--muted); font-size: 10px; font-weight: 700; text-transform: uppercase; margin-top: 2px; }

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
    .or-divider::before, .or-divider::after { content: ''; position: absolute; top: 50%; width: 35%; height: 1px; background: var(--line); }
    .or-divider::before { left: 0; } .or-divider::after { right: 0; }

    .extra-section { background: var(--bg-extra); border-radius: 16px; padding: 15px; margin: 15px 0; border: 1px solid #dbeafe; }
    .extra-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px; }

    .btn-submit-order { 
        width: 100%; background: linear-gradient(135deg, var(--accent), #3b82f6); color: white; 
        border-radius: 12px; padding: 14px; font-weight: 700; border: none; cursor: pointer;
        text-transform: uppercase; font-size: 12px; margin-top: 10px;
    }
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
            <input type="number" name="cost_price" id="cost_price_input" step="0.01" class="custom-input" placeholder="0.00" required>
        </div>

        <div class="extra-section">
            <label class="group-label" style="color: #1e40af;">✨ Detalles de Personalización</label>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="has_dorsal" id="has_dorsal" value="1" style="width: 16px; height: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #1e40af;">Añadir Dorsal (+1.71€)</span>
                </label>
                <div id="dorsal_details" class="extra-grid hidden">
                    <input type="text" name="dorsal_name" class="custom-input" placeholder="Nombre (Ej: MESSI)">
                    <input type="text" name="dorsal_number" class="custom-input" placeholder="Número (Ej: 10)">
                </div>
            </div>

            <div>
                <select name="patches_qty" id="patches_qty" class="custom-input" style="border-color: #bfdbfe; margin-bottom: 8px;">
                    <option value="0">Sin parches</option>
                    <option value="1">1 Parche (+0.86€)</option>
                    <option value="2">2 Parches (+1.72€)</option>
                </select>
                <div id="patch_details" class="hidden">
                    <input type="text" name="patches_description" class="custom-input" placeholder="¿Qué parches son? (Ej: Champions y Respect)">
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #bfdbfe;">
                <span style="font-size: 10px; font-weight: 800; color: #60a5fa; text-transform: uppercase;">Total por unidad:</span>
                <span id="final_computed_label" style="font-size: 16px; font-weight: 900; color: #1e40af;">0.00€</span>
            </div>
        </div>

        <button type="submit" class="btn-submit-order">
            ＋ Confirmar Entrada
        </button>
    </form>
</div>

<script>
    const costInput = document.getElementById('cost_price_input');
    const patchesSelect = document.getElementById('patches_qty');
    const dorsalCheck = document.getElementById('has_dorsal');
    const finalLabel = document.getElementById('final_computed_label');

    // Mostrar/Ocultar detalles dinámicamente
    dorsalCheck.addEventListener('change', function() {
        document.getElementById('dorsal_details').classList.toggle('hidden', !this.checked);
        updateComputedPrice();
    });

    patchesSelect.addEventListener('change', function() {
        document.getElementById('patch_details').classList.toggle('hidden', this.value == "0");
        updateComputedPrice();
    });

    function updateComputedPrice() {
        const base = parseFloat(costInput.value) || 0;
        const patches = parseInt(patchesSelect.value) || 0;
        const total = base + (patches * 0.86) + (dorsalCheck.checked ? 1.71 : 0);
        finalLabel.innerText = total.toFixed(2) + '€';
    }

    costInput.addEventListener('input', updateComputedPrice);

    // Lógica catálogo/manual
    document.getElementById('product_select_order').addEventListener('change', function() {
        const manualFields = document.getElementById('manual-fields-order');
        const divider = document.querySelector('.or-divider');
        manualFields.classList.toggle('hidden', !!this.value);
        divider.classList.toggle('hidden', !!this.value);
    });
</script>