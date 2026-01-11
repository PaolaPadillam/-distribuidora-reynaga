@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-3">Reporte de Inventario</h2>

        {{-- filtros --}}
        <form class="row g-2 align-items-end mb-3" method="GET" action="{{ route('reportes.inventario') }}">
            <div class="col-md-4">
                <label class="form-label">Buscar producto</label>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Nombre del producto...">
            </div>

            <div class="col-md-3">
                <label class="form-label">Proveedor</label>
                <select name="proveedor_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id_proveedor ?? $p->id }}"
                            {{ (string)($proveedorId ?? '') === (string)($p->id_proveedor ?? $p->id) ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5 text-end">
                <button class="btn btn-primary">Aplicar</button>

                @if(class_exists(\Barryvdh\DomPDF\ServiceProvider::class))
                    <a href="{{ route('reportes.inventario.export.pdf', request()->query()) }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                    </a>
                @endif
            </div>
        </form>

        {{-- tarjetas resumen --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Productos</div>
                    <div class="fs-4 fw-bold">{{ $totalProductos }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Total unidades (stock)</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalStock, 0) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Valor (mayoreo)</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalValueMayoreo, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Productos bajo stock (&lt; {{ $lowThreshold }})</div>
                    <div class="fs-4 fw-bold text-danger">{{ $lowStockCount }}</div>
                </div>
            </div>
        </div>



        {{-- gráfica --}}
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="inventarioChart" style="height: 350px !important;"></canvas>
            </div>
        </div>

        {{-- tabla productos --}}
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                    <tr>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Unidad</th>
                        <th>Precio mayoreo</th>
                        <th>Precio menudeo</th>
                        <th>Valor (mayoreo)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($productos as $prod)
                        <tr @if($prod->stock < $lowThreshold) class="table-danger" @endif>
                            <td class="text-start">{{ $prod->nombre_producto }}</td>
                            <td class="text-start">{{ $prod->descripcion }}</td>
                            <td>{{ $prod->stock }}</td>
                            <td>{{ $prod->unidad }}</td>
                            <td>${{ number_format($prod->precio_mayoreo,2) }}</td>
                            <td>${{ number_format($prod->precio_menudeo,2) }}</td>
                            <td>${{ number_format($prod->stock * $prod->precio_mayoreo,2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No hay productos</td></tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labelsInv = {!! json_encode($labels) !!};
        const dataInv = {!! json_encode($dataStock) !!};

        const ctxInv = document.getElementById('inventarioChart');

        new Chart(ctxInv, {
            type: 'bar',
            data: {
                labels: labelsInv,
                datasets: [{
                    label: 'Stock por producto',
                    data: dataInv,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45,
                            color: '#000'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#000' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
@endsection
