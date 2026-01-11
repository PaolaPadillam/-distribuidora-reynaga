@extends('layouts.app')

@section('content')
    <style>
        .report-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
            border-radius: 15px;
        }

        .report-card:hover {
            transform: translateY(-6px);
            box-shadow: 0px 10px 25px rgba(0,0,0,0.15);
        }

        .icon-large {
            font-size: 3.5rem;
        }

        /* Colores personalizados */
        .card-inventario {
            background: linear-gradient(135deg, #0d6efd, #4dabff);
            color: white;
        }

        .card-ventas {
            background: linear-gradient(135deg, #198754, #44c78c);
            color: white;
        }

        .card-proveedores {
            background: linear-gradient(135deg, #fd7e14, #ffb066);
            color: white;
        }

        .card-clientes {
            background: linear-gradient(135deg, #6f42c1, #b28dff);
            color: white;
        }

        .card-compras {
            background: linear-gradient(135deg, #dc3545, #ff8a94);
            color: white;
        }
    </style>

    <div class="container mt-4">

        <h2 class="text-primary fw-bold mb-4">Panel de Reportes</h2>

        <div class="row g-4">

            {{-- Inventario --}}
            <div class="col-md-4">
                <a href="{{ route('reportes.inventario') }}" class="text-decoration-none">
                    <div class="card report-card card-inventario p-4 text-center">
                        <i class="bi bi-box-seam icon-large"></i>
                        <h4 class="mt-3 fw-bold">Reporte de Inventario</h4>
                        <p class="mt-2">Existencias, bajo stock, valores y análisis general.</p>
                    </div>
                </a>
            </div>

            {{-- Ventas --}}
            <div class="col-md-4">
                <a href="{{ route('reportes.ventas') }}" class="text-decoration-none">
                    <div class="card report-card card-ventas p-4 text-center">
                        <i class="bi bi-cash-coin icon-large"></i>
                        <h4 class="mt-3 fw-bold">Reporte de Ventas</h4>
                        <p class="mt-2">Total vendido, productos más vendidos, ingresos diarios.</p>
                    </div>
                </a>
            </div>

            {{-- Proveedores --}}
            <div class="col-md-4">
                <a href="{{ route('reportes.proveedores') }}" class="text-decoration-none">
                    <div class="card report-card card-proveedores p-4 text-center">
                        <i class="bi bi-truck icon-large"></i>
                        <h4 class="mt-3 fw-bold">Reporte de Proveedores</h4>
                        <p class="mt-2">Productos por proveedor, compras y movimientos.</p>
                    </div>
                </a>
            </div>

            {{-- Clientes --}}
            <div class="col-md-4">
                <a href="{{ route('reportes.clientes.index') }}" class="text-decoration-none">
                    <div class="card report-card card-clientes p-4 text-center">
                        <i class="bi bi-people icon-large"></i>
                        <h4 class="mt-3 fw-bold">Reporte de Clientes</h4>
                        <p class="mt-2">Compras por cliente, historial y créditos.</p>
                    </div>
                </a>
            </div>



        </div>
    </div>
@endsection
