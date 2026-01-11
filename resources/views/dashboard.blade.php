<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Distribuidora de Abarrotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #00aaff);
            min-height: 100vh;
            color: #fff;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
        }
        .card {
            border: none;
            border-radius: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .card:hover {
            transform: translateY(-7px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        .card .bi {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 15px;
        }
        .card-title {
            color: #007bff;
            font-weight: bold;
        }
        .card-text {
            color: #555;
        }
        .logo {
            width: 50px;
            margin-right: 10px;
        }
        .welcome {
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark p-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('imagenes/logo.png') }}" class="logo" alt="Logo">
            Distribuidora de Abarrotes
        </a>
        <div>
            <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Bienvenida -->
<div class="container text-center my-5">
    <h1 class="fw-bold welcome">¡Bienvenido, REYNAGA!</h1>
    <p class="lead">Selecciona una opción para comenzar</p>
</div>

<!-- Tarjetas de opciones -->
<div class="container pb-5">
    <div class="row g-4 justify-content-center">

        <!-- Clientes -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('clientes.index') }}" class="text-decoration-none">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-people-fill"></i>
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Gestión de clientes y ventas.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Proveedores -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('proveedores.index') }}" class="text-decoration-none">
            <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-truck"></i>
                        <h5 class="card-title">Proveedores</h5>
                        <p class="card-text">Control de proveedores y compras.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Rutas -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('rutas.index') }}" class="text-decoration-none">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-map"></i>
                        <h5 class="card-title">Rutas</h5>
                        <p class="card-text">Planificación y seguimiento de rutas.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Inventario -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('productos.index') }}" class="text-decoration-none">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-box-seam"></i>
                        <h5 class="card-title">Inventario</h5>
                        <p class="card-text">Registro y control de productos.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Ventas -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('ventas.index') }}" class="text-decoration-none">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-cart-check"></i>
                        <h5 class="card-title">Ventas</h5>
                        <p class="card-text">Registro y gestión de ventas.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Créditos -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('creditos.index') }}" class="text-decoration-none">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-credit-card-2-front"></i>
                        <h5 class="card-title">Créditos</h5>
                        <p class="card-text">Control de créditos y saldos pendientes.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Reportes -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('reportes.index') }}" class="text-decoration-none">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h5 class="card-title">Reportes</h5>
                        <p class="card-text">Visualiza estadísticas y reportes.</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
