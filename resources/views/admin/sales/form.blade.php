<style>
    :root {
        --card: #ffffff;
        --line: #e5e7eb;
        --text: #111827;
        --muted: #6b7280;
        --accent: #2563eb;
        --radius: 22px;
        --shadow: 0 12px 34px rgba(0,0,0,.15);
    }

    .sales-wrapper { max-width: 500px; margin: 0 auto; background: white; border-radius: var(--radius); overflow: hidden; }
    .sales-card { background: var(--card); border: 1px solid var(--line); }
    .sales-header { background: #f9fafb; padding: 24px; border-bottom: 1px solid var(--line); text-align: center; }
    .sales-header h3 { font-weight: 800; font-size: 16px; color: var(--text); text-transform: uppercase; letter-spacing: 1.5px; margin: 0; }
    .sales-form { padding: 30px; }
    .group-label { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; margin-bottom: 8px; display: block; letter-spacing: 0.5px; }
    .custom-input { width: 100%; background: #f9fafb; border: 1px solid var(--line); border-radius: 14px; padding: 12px 16px; font-size: 14px; font-weight: 600; color: var(--text); transition: all 0.2s ease; margin-bottom: 15px; }
    .custom-input:focus { background: white; border-color: var(--accent); outline: none; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    .btn-submit-sales { width: 100%; background: linear-gradient(135deg, var(--accent), #3b82f6); color: white; border-radius: 14px; padding: 16px; font-weight: 700; border: none; box-shadow: 0 10px 20px rgba(37,99,235,.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.2s; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; margin-top: 10px; }
    .btn-submit-sales:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(37,99,235,.4); }
    .hidden { display: none !important; }
    .info-box { background: #eff6ff; padding: 12px; border-radius: 12px; border: 1px solid #bfdbfe; margin-bottom: 20px; font-size: 12px; color: #1e40af; font-weight: 600; }
</style>

<div class="sales-wrapper">
    <div class="sales-card">
        <div class="sales-header">
            <h3 id="sales-modal-title">Registrar Nueva Venta</h3>
        </div>

        <form action="{{ route('sales.store') }}" method="POST" class="sales-form" id="sales-main-form">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label class="group-label">Vendedor</label>
                    <select name="seller_name" id="seller_select" class="custom-input" required>
                        <option value="Web">Web</option>
                        <option value="Elias" selected>Elias</option>
                        <option value="Mariano">Mariano</option>
                        <option value="Dani">Dani</option>
                        <option value="Jorge">Jorge</option>
                        <option value="Rafa">Rafa</option>
                    </select>
                </div>
                <div>
                    <label class="group-label">Fecha</label>
                    <input type="date" name="sale_date" class="custom-input" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div>
                <label class="group-label">Producto en Stock</label>
                <select name="inventory_id" id="inventory_select_form" class="custom-input" required>
                    <option value="">-- Selecciona producto --</option>
                    @foreach($availableStock as $item)
                        <option value="{{ $item->id }}" data-cost="{{ $item->cost_price }}">
                            {{ $item->product->name ?? 'Carga Manual' }} (Talla: {{ $item->size }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="cost_info_box" class="info-box hidden">
                Coste de adquisición: <span id="display_cost">0.00</span>€
            </div>

            <input type="hidden" name="cost_price" id="cost_hidden_input">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label class="group-label">Precio Venta (€)</label>
                    <input type="number" name="sale_price" id="sale_price_input" step="0.01" class="custom-input" 
                           style="color: var(--accent); font-weight: 800;" placeholder="0.00" required>
                </div>
                <div>
                    <label class="group-label">Comisión (€)</label>
                    <input type="number" name="commission" id="commission_input" step="0.01" class="custom-input" 
                           style="color: #f59e0b; font-weight: 800;" value="0.00" required>
                </div>
            </div>

            <button type="submit" class="btn-submit-sales">
                <span>💰</span> Confirmar Venta
            </button>
        </form>
    </div>
</div>

<script>
    const inventorySelect = document.getElementById('inventory_select_form');
    const sellerSelect = document.getElementById('seller_select');
    const salePriceInput = document.getElementById('sale_price_input');
    const commissionInput = document.getElementById('commission_input');
    const hiddenCostInput = document.getElementById('cost_hidden_input');

    // Función unificada para calcular comisión
    function updateCommission() {
        const cost = parseFloat(hiddenCostInput.value) || 0;
        const sale = parseFloat(salePriceInput.value) || 0;
        const seller = sellerSelect.value;

        if (seller === 'Web') {
            commissionInput.value = "0.00";
            return;
        }

        if (sale > cost) {
            const profit = sale - cost;
            const autoCommission = profit * 0.20;
            commissionInput.value = autoCommission.toFixed(2);
        } else {
            commissionInput.value = "0.00";
        }
    }

    // Escuchar cambios manuales
    salePriceInput.addEventListener('input', updateCommission);
    sellerSelect.addEventListener('change', updateCommission);

    inventorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const cost = selectedOption.getAttribute('data-cost');
        const infoBox = document.getElementById('cost_info_box');
        const displayCost = document.getElementById('display_cost');

        if (cost) {
            infoBox.classList.remove('hidden');
            displayCost.textContent = parseFloat(cost).toFixed(2);
            hiddenCostInput.value = cost;
            updateCommission(); 
        } else {
            infoBox.classList.add('hidden');
            hiddenCostInput.value = '';
        }
    });

    // FUNCIÓN GLOBAL: Para llamar desde el botón de la tabla
    window.fillSaleForm = function(inventoryId, costPrice, productName) {
        // 1. Resetear formulario
        document.getElementById('sales-main-form').reset();
        
        // 2. Asignar Producto
        inventorySelect.value = inventoryId;
        
        // 3. Asignar Coste
        hiddenCostInput.value = costPrice;
        document.getElementById('display_cost').textContent = parseFloat(costPrice).toFixed(2);
        document.getElementById('cost_info_box').classList.remove('hidden');
        
        // 4. Cambiar título para confirmar qué vendemos
        document.getElementById('sales-modal-title').innerText = "Vender: " + productName;
        
        // 5. Reiniciar comisión
        updateCommission();
    }
</script>