@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <h2 class="text-primary fw-bold mb-3">Reporte de Proveedores</h2>

        {{-- Filtros --}}
        <form action="{{ route('reportes.proveedores') }}" method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-md-4">
                <label class="form-label">Buscar proveedor</label>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Nombre del proveedor...">
            </div>

            <div class="col-md-4 text-end">
                <button class="btn btn-primary">Aplicar</button>

                <a href="{{ route('reportes.proveedores.export.pdf', request()->query()) }}"
                   class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                </a>
            </div>
        </form>

        {{-- Tarjetas --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <span class="text-muted">Total Proveedores</span>
                    <div class="fs-3 fw-bold">{{ $totalProveedores }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <span class="text-muted">Productos Registrados</span>
                    <div class="fs-3 fw-bold">{{ $totalProductos }}</div>
                </div>
            </div>
        </div>

        {{-- Gráfica --}}
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="proveedoresChart" height="80"></canvas>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                    <tr class="text-center">
                        <th>Proveedor</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Productos</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($proveedores as $prov)
                        <tr>
                            <td>{{ $prov->nombre }}</td>
                            <td>{{ $prov->telefono }}</td>
                            <td>{{ $prov->email }}</td>
                            <td class="text-center">{{ $prov->productos()->count() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay proveedores registrados</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $proveedores->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const provLabels = {!! json_encode($proveedorLabels) !!};
        const provCounts = {!! json_encode($proveedorCounts) !!};

        const ctx = document.getElementById('proveedoresChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: provLabels,
                datasets: [{
                    label: 'Productos por proveedor',
                    data: provCounts,
                    backgroundColor: 'rgba(99, 132, 255, 0.6)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
@endsection
