<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esencia Fut | Tienda Oficial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f9fafb; color: #111827; }
        .shop-nav { padding: 1.5rem 0; border-bottom: 1px solid #f3f4f6; background: white; position: sticky; top: 0; z-index: 1000; }
        .brand-name { font-weight: 800; font-size: 1.6rem; letter-spacing: -1px; text-decoration: none; color: #000; }
        .nav-icon-link { color: #4b5563; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: 0.2s; }
        .nav-icon-link:hover { color: #000; }
        .admin-access { background: #111827; color: #fff !important; padding: 8px 16px; border-radius: 6px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
        
        .product-card { border: none; border-radius: 15px; transition: transform 0.3s ease; background: white; overflow: hidden; height: 100%; }
        .product-card:hover { transform: translateY(-5px); }
        .product-img { height: 300px; object-fit: cover; width: 100%; }
        .badge-version { position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 5px 10px; border-radius: 8px; font-weight: 700; font-size: 10px; text-transform: uppercase; }
    </style>
</head>
<body>

    <nav class="shop-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="brand-name">ESENCIA FUT</a>
            <div class="d-flex align-items-center gap-4">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('products.list') }}" class="nav-icon-link admin-access">Panel Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-icon-link p-0 border-0">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-icon-link">Entrar</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="bg-white p-4 rounded-4 shadow-sm mb-5">
            <form action="{{ route('catalog') }}" method="GET" id="filter-form" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar camiseta..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="competition" id="competition-select" class="form-select">
                        <option value="">Todas las competiciones</option>
                        @foreach($competitions as $comp)
                            <option value="{{ $comp->id }}" {{ request('competition') == $comp->id ? 'selected' : '' }}>
                                {{ $comp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="team" id="team-select" class="form-select" {{ !request('competition') ? 'disabled' : '' }}>
                        <option value="">Todos los equipos</option>
                        @if(isset($teams))
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                
                <div class="col-md-2 d-flex gap-2">
                    <a href="{{ route('catalog') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.059 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                    </a>
                    <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card shadow-sm position-relative">
                        <span class="badge-version text-dark">{{ $product->version_type }}</span>
                        @if($product->images->count())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="product-img" alt="{{ $product->name }}">
                        @else
                            <div class="product-img bg-light d-flex align-items-center justify-content-center text-muted">Sin imagen</div>
                        @endif
                        <div class="p-3">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2">{{ $product->team->name ?? 'Equipo' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-extrabold fs-5">{{ number_format($product->cost, 2) }}€</span>
                                <a href="#" class="btn btn-sm btn-outline-dark rounded-pill">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">No hemos encontrado camisetas.</h4>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    <footer class="py-5 bg-light mt-5 border-top">
        <div class="container text-center">
            <p class="text-muted small mb-0">&copy; 2026 Esencia Fut - Calidad Premium</p>
        </div>
    </footer>

    <script>
        const compSelect = document.getElementById('competition-select');
        const teamSelect = document.getElementById('team-select');
        const form = document.getElementById('filter-form');

        compSelect.addEventListener('change', function() {
            const compId = this.value;
            
            // Si no hay competición, desactivamos equipos
            if (!compId) {
                teamSelect.innerHTML = '<option value="">Todos los equipos</option>';
                teamSelect.disabled = true;
                form.submit();
                return;
            }

            // Llamada AJAX para obtener equipos
            fetch(`/api/teams/${compId}`)
                .then(response => response.json())
                .then(data => {
                    teamSelect.disabled = false;
                    teamSelect.innerHTML = '<option value="">Todos los equipos</option>';
                    data.forEach(team => {
                        const opt = document.createElement('option');
                        opt.value = team.id;
                        opt.textContent = team.name;
                        teamSelect.appendChild(opt);
                    });
                    // Opcional: form.submit(); si quieres que filtre al elegir liga
                });
        });

        // Enviar formulario al elegir equipo
        teamSelect.addEventListener('change', () => form.submit());
    </script>

</body>
</html>