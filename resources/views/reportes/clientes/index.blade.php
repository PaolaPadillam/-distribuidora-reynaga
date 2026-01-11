@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-3">Reporte de Clientes</h2>

        <form class="row g-2 align-items-end mb-3" method="GET" action="{{ route('reportes.clientes.index') }}">

            <!-- FILTRO CLIENTE -->
            <div class="col-md-4">
                <label class="form-label">Cliente</label>
                <select name="cliente_id" class="form-select" required>
                    <option value="">Selecciona un cliente...</option>
                    @foreach($clientesLista as $cli)
                        <option value="{{ $cli->id }}"
                            {{ (string)$clienteId === (string)$cli->id ? 'selected' : '' }}>
                            {{ $cli->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- FILTRO FECHA DESDE -->
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" value="{{ $desde ?? '' }}" class="form-control">
            </div>

            <!-- FILTRO FECHA HASTA -->
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" value="{{ $hasta ?? '' }}" class="form-control">
            </div>

            <!-- BOTONES -->
            <div class="col-md-2 text-end">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Aplicar
                </button>
            </div>

            <div class="col-md-12 text-end mt-2">
                <a href="{{ route('reportes.clientes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Limpiar filtros
                </a>

                @if(class_exists(\Barryvdh\DomPDF\ServiceProvider::class))
                    <a href="{{ route('reportes.clientes.export.pdf', request()->query()) }}" class="btn btn-outline-danger ms-2">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                @endif
            </div>

        </form>


        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Total vendido (rango)</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalVendido,2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Ventas</div>
                    <div class="fs-4 fw-bold">{{ $cantidadVentas }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Mayoreo</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalMayoreo,2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Menudeo</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalMenudeo,2) }}</div>
                </div>
            </div>
        </div>

        {{-- Gráfica TOP compradores --}}
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="clientesTopChart" height="80"></canvas>
            </div>
        </div>

        {{-- Tabla clientes --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary text-center">
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Créditos pendientes</th>
                            <th>Total comprado (últ. año)</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clientes as $c)
                            @php
                                $totalCliente = \App\Models\Venta::where('id_cliente',$c->id)
                                    ->whereBetween('fecha', [$desde, $hasta])->sum('total');
                                $creditoPendiente = \App\Models\Credito::where('id_cliente',$c->id)->sum('saldo_pendiente');
                            @endphp
                            <tr>
                                <td class="text-start">{{ $c->nombre }}</td>
                                <td>{{ $c->telefono }}</td>
                                <td>{{ $c->email }}</td>
                                <td>{{ ucfirst($c->tipo_cliente) }}</td>
                                <td>${{ number_format($creditoPendiente,2) }}</td>
                                <td>${{ number_format($totalCliente,2) }}</td>
                                <td>
                                    <a href="{{ route('reportes.clientes.show', $c->id) }}" class="btn btn-sm btn-info">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labelsClientes = {!! json_encode($labels) !!};
        const dataClientes = {!! json_encode($data) !!};

        const ctx = document.getElementById('clientesTopChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsClientes,
                datasets: [{
                    label: 'Total comprado',
                    data: dataClientes,
                    backgroundColor: 'rgba(54,162,235,0.6)'
                }]
            },
            options: {
                indexAxis: 'x',
                scales: {
                    x: { ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 } },
                    y: { beginAtZero: true }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
