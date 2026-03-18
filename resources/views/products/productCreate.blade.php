<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="container-fluid px-3 py-3">
        <style>
            :root {
                --bg: #0b0f19;
                --card: #ffffff;
                --line: #e5e7eb;
                --text: #111827;
                --muted: #6b7280;
                --accent: #2563eb;
                --accent-hover: #1d4ed8;
                --shadow: 0 10px 24px rgba(0,0,0,.16);
            }

            /* Quitamos el body background para no romper el layout general de Breeze */
            .create-page { max-width: 1180px; margin: 0 auto; padding-top: 20px; }
            .create-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
            .create-title h1 { margin: 0; font-size: 24px; font-weight: 800; color: #111827; } /* Ajustado a oscuro para el layout */
            .create-title p { margin: 4px 0 0; font-size: 13px; color: #6b7280; }
            
            .back-btn { display: inline-flex; align-items: center; gap: 8px; height: 42px; padding: 0 14px; border-radius: 12px; background: #f3f4f6; border: 1px solid #e5e7eb; color: #374151; text-decoration: none; font-weight: 700; font-size: 13px; transition: .2s; }
            .back-btn:hover { background: #e5e7eb; color: #000; }

            .create-card { background: var(--card); border-radius: 22px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--line); }
            .create-card-top { padding: 16px 20px; border-bottom: 1px solid var(--line); background: #fafafa; }
            .create-card-top h2 { margin: 0; font-size: 18px; font-weight: 800; color: var(--text); }
            .create-card-body { padding: 25px; }

            .names-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
            .fields-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
            
            .field-block { display: flex; flex-direction: column; }
            .field-block.full-width { grid-column: span 4; }
            
            .form-label { font-size: 12px; font-weight: 800; color: #374151; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
            .form-control, .form-select { height: 46px; border-radius: 12px; border: 1px solid #dbe3ee; padding: 10px 14px; font-size: 14px; transition: 0.2s; background-color: #fff; }
            .form-control:focus, .form-select:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
            
            .form-control[readonly] { background: #f8fafc; color: #64748b; font-weight: 700; cursor: not-allowed; border-style: dashed; }
            .supplier-input { border: 1px solid #93c5fd; background: #f0f7ff; }

            .upload-box { border: 2px dashed #cbd5e1; border-radius: 16px; background: #f8fafc; padding: 20px; text-align: center; transition: 0.3s; }
            .upload-box:hover { border-color: var(--accent); background: #eff6ff; }
            
            .btn-save { height: 48px; padding: 0 30px; border: 0; border-radius: 12px; background: var(--accent); color: #ffffff; font-weight: 800; cursor: pointer; transition: .2s; font-size: 15px; }
            .btn-save:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
            .btn-cancel { height: 48px; padding: 0 24px; border-radius: 12px; border: 1px solid #d1d5db; background: #fff; color: #374151; text-decoration: none; display: inline-flex; align-items: center; font-weight: 700; }

            @media (max-width: 768px) {
                .names-grid, .fields-grid { grid-template-columns: 1fr; }
                .field-block.full-width { grid-column: span 1; }
            }
        </style>

        <div class="create-page">
            <div class="create-header">
                <div class="create-title">
                    <h1>Crear nuevo producto</h1>
                    <p>Configura los detalles de la camiseta y el catálogo.</p>
                </div>
                <a href="{{ route('products.list') }}" class="back-btn">← Volver al listado</a>
            </div>

            <div class="create-card">
                <div class="create-card-top"><h2>Detalles del Producto</h2></div>
                <div class="create-card-body">
                    
                    @if ($errors->any())
                        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fecaca;">
                            <strong style="display: block; margin-bottom: 5px;">⚠️ Revisa los siguientes errores:</strong>
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="names-grid">
                            <div class="field-block">
                                <label class="form-label">Nombre Web (Auto-generado)</label>
                                <input type="text" name="name" id="generated_name" class="form-control" readonly placeholder="Se generará automáticamente..." required>
                            </div>
                            <div class="field-block">
                                <label class="form-label">Nombre del Producto en Proveedor</label>
                                <input type="text" name="supplier_product_name" class="form-control supplier-input" placeholder="Ej: RM 24/25 Player Home White">
                            </div>
                        </div>

                        <div class="fields-grid">
                            <div class="field-block">
                                <label class="form-label">Competición</label>
                                <select name="competition_id" id="competition_id" class="form-select" required>
                                    <option value="">Selecciona...</option>
                                    @foreach($competitions as $comp)
                                        <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field-block">
                                <label class="form-label">Equipo</label>
                                <select name="team_id" id="team_id" class="form-select" required>
                                    <option value="">Selecciona equipo</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" data-competition="{{ $team->competition_id }}" data-name="{{ $team->name }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field-block">
                                <label class="form-label">Temporada</label>
                                <select name="season_id" id="season_id" class="form-select">
                                    <option value="">Selecciona</option>
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->id }}" data-name="{{ $season->name }}">{{ $season->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="season_manual" id="season_manual" class="form-control" placeholder="Ej: 1998-99" style="display: none;">
                            </div>

                            <div class="field-block">
                                <label class="form-label">Kit</label>
                                <select name="kit_type" id="kit_type" class="form-select" required>
                                    <option value="home">Home</option>
                                    <option value="away">Away</option>
                                    <option value="third">Third</option>
                                    <option value="special">Special</option>
                                </select>
                            </div>

                            <div class="field-block">
                                <label class="form-label">Versión</label>
                                <select name="version_type" id="version_type" class="form-select" required>
                                    <option value="fan">Fan (28.00€)</option>
                                    <option value="player">Player (30.00€)</option>
                                    <option value="retro">Retro (30.00€)</option>
                                </select>
                            </div>

                            <div class="field-block">
                                <label class="form-label">Coste final (€)</label>
                                <input type="number" step="0.01" name="cost" id="cost_input" class="form-control" value="28.00">
                            </div>

                            <div class="field-block">
                                <label class="form-label">Proveedor</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="">Sin asignar</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field-block">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>

                            <div class="field-block full-width">
                                <div class="upload-box">
                                    <label class="form-label">Subir imágenes del producto</label>
                                    <input type="file" name="images[]" class="form-control" multiple style="border: none; background: transparent;">
                                    <p style="font-size: 11px; color: #64748b; margin-top: 8px;">Puedes seleccionar varios archivos a la vez.</p>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 25px;">
                            <a href="{{ route('products.list') }}" class="btn-cancel">Cancelar</a>
                            <button type="submit" class="btn-save">Crear Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const staticPrices = { 'fan': '28.00', 'player': '30.00', 'retro': '30.00' };

        function updateProductLogic() {
            const versionValue = document.getElementById('version_type').value;
            const seasonSelect = document.getElementById('season_id');
            const seasonManual = document.getElementById('season_manual');
            
            // 1. Alternar entre Selector e Input de Temporada
            if (versionValue === 'retro') {
                seasonSelect.style.display = 'none';
                seasonManual.style.display = 'block';
            } else {
                seasonSelect.style.display = 'block';
                seasonManual.style.display = 'none';
            }

            // 2. Actualizar Coste
            document.getElementById('cost_input').value = staticPrices[versionValue] || '0.00';

            // 3. Generar Nombre Automático
            const teamSelect = document.getElementById('team_id');
            const kitSelect = document.getElementById('kit_type');
            
            const teamName = teamSelect.options[teamSelect.selectedIndex]?.dataset.name || '';
            const seasonName = (versionValue === 'retro') 
                ? seasonManual.value 
                : seasonSelect.options[seasonSelect.selectedIndex]?.dataset.name || '';
                
            const kitName = kitSelect.value.charAt(0).toUpperCase() + kitSelect.value.slice(1);
            const versionName = versionValue.charAt(0).toUpperCase() + versionValue.slice(1);

            const fullName = `${teamName} ${seasonName} ${kitName} ${versionName}`.replace(/\s+/g, ' ').trim();
            document.getElementById('generated_name').value = fullName;
        }

        // Filtro de equipos por competición
        document.getElementById('competition_id').addEventListener('change', function() {
            const selectedComp = this.value;
            const teamSelect = document.getElementById('team_id');
            Array.from(teamSelect.options).forEach(opt => {
                if (opt.value) opt.hidden = (opt.dataset.competition !== selectedComp);
            });
            teamSelect.value = '';
            updateProductLogic();
        });

        // Listeners
        document.getElementById('season_manual').addEventListener('input', updateProductLogic);
        ['team_id', 'season_id', 'kit_type', 'version_type'].forEach(id => {
            document.getElementById(id).addEventListener('change', updateProductLogic);
        });
    </script>
</x-app-layout>