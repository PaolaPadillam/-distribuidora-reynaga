<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora de Abarrotes</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />

    <!-- FIX Navbar afectado por FullCalendar -->
    <style>
        nav.navbar {
            position: relative !important;
            z-index: 1030 !important;
            min-height: 60px !important;
        }

        nav .navbar-brand img {
            height: 35px !important;
        }

        /* Evita que el navbar se haga gigante */
        .navbar-nav {
            flex-wrap: nowrap !important;
        }

        /* Ajusta los elementos y evita "saltos" */
        .navbar-nav .nav-link {
            white-space: nowrap !important;
        }

        /* Evitar que FullCalendar expanda todo */
        .fc {
            max-width: 100% !important;
        }
    </style>
</head>

<body style="background-color:#f7f9fc;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('imagenes/logo.png') }}" alt="Logo" class="me-2">
            <span class="fw-bold">Distribuidora</span>
        </a>

        <!-- BOTÓN RESPONSIVE -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENÚ DERECHA -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('clientes*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('clientes.index') }}">
                        Clientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('proveedores*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('proveedores.index') }}">
                        Proveedores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('rutas*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('rutas.index') }}">
                        Rutas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('productos*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('productos.index') }}">
                        Inventario
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('ventas*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('ventas.index') }}">
                        Ventas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('creditos*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('creditos.index') }}">
                        Créditos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('reportes*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('reportes.index') }}">
                        Reportes
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>


<!-- CONTENIDO -->
<main>
    @yield('content')
</main>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- FullCalendar -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>

@yield('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
        Swal.fire({ icon:'success', title:'¡Éxito!', text:'{{ session('success') }}' });
        @endif

        @if(session('error'))
        Swal.fire({ icon:'error', title:'Error', text:'{{ session('error') }}' });
        @endif
    });
</script>

@stack('scripts')

</body>
</html>
