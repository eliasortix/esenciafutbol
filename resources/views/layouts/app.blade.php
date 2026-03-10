<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a href="{{ route('products.list') }}" class="navbar-brand">Esencia Fut Admin</a>
        </div>
    </nav>

    @yield('content')
</body>
</html>