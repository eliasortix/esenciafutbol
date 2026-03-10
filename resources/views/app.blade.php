<div class="form-group">
    <label class="form-label">SKU</label>
    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}">
    @error('sku') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Nombre</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}">
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug ?? '') }}">
    @error('slug') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Descripción</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Estado</label>
    <select name="status" class="form-control">
        <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Activo</option>
        <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Section Type</label>
    <select name="section_type" class="form-control">
        <option value="league" {{ old('section_type', $product->section_type ?? '') == 'league' ? 'selected' : '' }}>League</option>
        <option value="national_team" {{ old('section_type', $product->section_type ?? '') == 'national_team' ? 'selected' : '' }}>National Team</option>
        <option value="retro" {{ old('section_type', $product->section_type ?? '') == 'retro' ? 'selected' : '' }}>Retro</option>
    </select>
    @error('section_type') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Temporada</label>
    <input type="text" name="season" class="form-control" value="{{ old('season', $product->season ?? '') }}">
    @error('season') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Kit Type</label>
    <select name="kit_type" class="form-control">
        <option value="">Selecciona</option>
        <option value="home" {{ old('kit_type', $product->kit_type ?? '') == 'home' ? 'selected' : '' }}>Home</option>
        <option value="away" {{ old('kit_type', $product->kit_type ?? '') == 'away' ? 'selected' : '' }}>Away</option>
        <option value="third" {{ old('kit_type', $product->kit_type ?? '') == 'third' ? 'selected' : '' }}>Third</option>
        <option value="special" {{ old('kit_type', $product->kit_type ?? '') == 'special' ? 'selected' : '' }}>Special</option>
    </select>
    @error('kit_type') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Version Type</label>
    <select name="version_type" class="form-control">
        <option value="fan" {{ old('version_type', $product->version_type ?? '') == 'fan' ? 'selected' : '' }}>Fan</option>
        <option value="player" {{ old('version_type', $product->version_type ?? '') == 'player' ? 'selected' : '' }}>Player</option>
    </select>
    @error('version_type') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Tipo de precio</label>
    <select name="price_type_id" class="form-control">
        <option value="">Selecciona</option>
        @foreach($priceTypes as $priceType)
            <option value="{{ $priceType->id }}" {{ old('price_type_id', $product->price_type_id ?? '') == $priceType->id ? 'selected' : '' }}>
                {{ $priceType->name }} - {{ $priceType->price }}€
            </option>
        @endforeach
    </select>
    @error('price_type_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Proveedor</label>
    <select name="supplier_id" class="form-control">
        <option value="">Sin proveedor</option>
        @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
            </option>
        @endforeach
    </select>
    @error('supplier_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Coste</label>
    <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost', $product->cost ?? '') }}">
    @error('cost') <div class="text-danger">{{ $message }}</div> @enderror
</div>