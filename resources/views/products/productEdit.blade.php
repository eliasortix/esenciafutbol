@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Editar producto</h1>
            <p class="text-muted mb-0">Modifica los datos y el nombre del proveedor</p>
        </div>
        <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">Volver al listado</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores detectados:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body border-bottom">
            <label class="form-label d-block fw-bold">Imágenes actuales</label>
            @if($product->images->count())
                <div class="d-flex flex-wrap gap-3">
                    @foreach($product->images as $image)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $image->path) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
                            <form action="{{ route('products.images.destroy', $image) }}" method="POST" class="mt-2" onsubmit="return confirm('¿Borrar imagen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">Eliminar</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">Sin imágenes.</p>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold">Nombre Catálogo</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-primary">Nombre del Proveedor (AliExpress/Yupo)</label>
                        <input type="text" name="supplier_product_name" class="form-control border-primary" 
                               value="{{ old('supplier_product_name', $product->supplier_product_name) }}" 
                               placeholder="Ej: Manchester City Home 24/25 Player Version">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sección</label>
                        <select name="section_type" class="form-select" required>
                            <option value="league" {{ old('section_type', $product->section_type) === 'league' ? 'selected' : '' }}>Liga</option>
                            <option value="national_team" {{ old('section_type', $product->section_type) === 'national_team' ? 'selected' : '' }}>Selección</option>
                            <option value="retro" {{ old('section_type', $product->section_type) === 'retro' ? 'selected' : '' }}>Retro</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Temporada</label>
                        <input type="text" name="season" class="form-control" value="{{ old('season', $product->season) }}" placeholder="24/25">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Kit</label>
                        <select name="kit_type" class="form-select">
                            <option value="">--</option>
                            <option value="home" {{ old('kit_type', $product->kit_type) === 'home' ? 'selected' : '' }}>Home</option>
                            <option value="away" {{ old('kit_type', $product->kit_type) === 'away' ? 'selected' : '' }}>Away</option>
                            <option value="third" {{ old('kit_type', $product->kit_type) === 'third' ? 'selected' : '' }}>Third</option>
                            <option value="special" {{ old('kit_type', $product->kit_type) === 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Versión / Tipo Precio</label>
                        <select name="version_type" id="version_type" class="form-select" required>
                            <option value="fan" data-price="28.00" {{ old('version_type', $product->version_type) === 'fan' ? 'selected' : '' }}>Fan (28€)</option>
                            <option value="player" data-price="30.00" {{ old('version_type', $product->version_type) === 'player' ? 'selected' : '' }}>Player (30€)</option>
                            <option value="retro" data-price="30.00" {{ old('version_type', $product->version_type) === 'retro' ? 'selected' : '' }}>Retro (30€)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Proveedor</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Sin proveedor asignado</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Coste (€)</label>
                        <input type="number" step="0.01" name="cost" id="cost_input" class="form-control" value="{{ old('cost', $product->cost) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Añadir más imágenes</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                    <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const versionSelect = document.getElementById('version_type');
    const costInput = document.getElementById('cost_input');

    versionSelect.addEventListener('change', function() {
        // Obtenemos el precio del atributo data-price de la opción seleccionada
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        
        if (price) {
            costInput.value = price;
            
            // Efecto visual de cambio
            costInput.style.transition = 'background-color 0.3s';
            costInput.style.backgroundColor = '#e8f0fe';
            setTimeout(() => costInput.style.backgroundColor = '', 500);
        }
    });
});
</script>
@endsection