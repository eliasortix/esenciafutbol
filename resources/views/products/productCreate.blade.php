@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Crear producto</h1>
            <p class="text-muted mb-0">Añade una nueva camiseta al catálogo</p>
        </div>

        <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">
            Volver al listado
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sección</label>
                        <select name="section_type" class="form-select" required>
                            <option value="league" {{ old('section_type') === 'league' ? 'selected' : '' }}>Liga</option>
                            <option value="national_team" {{ old('section_type') === 'national_team' ? 'selected' : '' }}>Selección</option>
                            <option value="retro" {{ old('section_type') === 'retro' ? 'selected' : '' }}>Retro</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Temporada</label>
                        <input type="text" name="season" class="form-control" value="{{ old('season') }}" placeholder="24/25">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Kit</label>
                        <select name="kit_type" class="form-select">
                            <option value="">--</option>
                            <option value="home" {{ old('kit_type') === 'home' ? 'selected' : '' }}>Home</option>
                            <option value="away" {{ old('kit_type') === 'away' ? 'selected' : '' }}>Away</option>
                            <option value="third" {{ old('kit_type') === 'third' ? 'selected' : '' }}>Third</option>
                            <option value="special" {{ old('kit_type') === 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Versión</label>
                        <select name="version_type" class="form-select" required>
                            <option value="fan" {{ old('version_type') === 'fan' ? 'selected' : '' }}>Fan</option>
                            <option value="player" {{ old('version_type') === 'player' ? 'selected' : '' }}>Player</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de precio</label>
                        <select name="price_type_id" class="form-select" required>
                            <option value="">Selecciona</option>
                            @foreach($priceTypes as $priceType)
                                <option value="{{ $priceType->id }}" {{ old('price_type_id') == $priceType->id ? 'selected' : '' }}>
                                    {{ $priceType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Proveedor</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Sin proveedor</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Coste</label>
                        <input type="number" step="0.01" min="0" name="cost" class="form-control" value="{{ old('cost') }}" placeholder="0.00">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Crear producto
                    </button>

                    <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection