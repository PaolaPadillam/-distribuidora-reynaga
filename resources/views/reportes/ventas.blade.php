@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-3">Reporte de Ventas</h2>

        {{-- Filtros --}}
        <form class="row g-2 align-items-end mb-3" method="GET" action="{{ route('reportes.ventas') }}">
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" value="{{ $desde ?? '' }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" value="{{ $hasta ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cliente</label>
                <select name="cliente_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ (string)($clienteId ?? '') === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipo de pago</label>
                <select name="tipo_pago" class="form-select">
                    <option value="">Todos</option>
                    <option value="contado" {{ ($tipoPago ?? '') === 'contado' ? 'selected' : '' }}>Contado</option>
                    <option value="credito" {{ ($tipoPago ?? '') === 'credito' ? 'selected' : '' }}>Crédito</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-primary">Aplicar</button>

                @if(class_exists(\Barryvdh\DomPDF\ServiceProvider::class))
                    <a href="{{ route('reportes.ventas.export.pdf', request()->query()) }}" class="btn btn-outline-secondary">Exportar PDF</a>
                @endif


            </div>
        </form>

        {{-- Tarjetas resumen --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Total vendido</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalVendido, 2) }}</div>
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
                    <div class="h6 text-muted">Contado</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalContado, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="h6 text-muted">Crédito</div>
                    <div class="fs-4 fw-bold">${{ number_format($totalCredito, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- Gráfica --}}
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="ventasChart" height="80"></canvas>
            </div>
        </div>

        {{-- Tabla de ventas --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary text-center">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ventas as $v)
                            <tr class="text-center">
                                <td>{{ $v->id_venta }}</td>
                                <td class="text-start">{{ $v->cliente->nombre ?? 'Sin cliente' }}</td>
                                <td>{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}</td>
                                <td>${{ number_format($v->total, 2) }}</td>
                                <td>{{ ucfirst($v->tipo_pago) }}</td>
                                <td>{{ ucfirst($v->estado) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No hay ventas en este rango</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $ventas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($labels) !!};
        const dataVentas = {!! json_encode($dataVentas) !!};

        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas (monto)',
                    data: dataVentas,
                    tension: 0.3,
                    fill: false,
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    x: { display: true },
                    y: { display: true, beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
@endsection
