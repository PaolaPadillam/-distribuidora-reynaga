@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-3">Cliente: {{ $cliente->nombre }}</h2>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="h6">Datos</div>
                    <div><strong>Teléfono:</strong> {{ $cliente->telefono }}</div>
                    <div><strong>Email:</strong> {{ $cliente->email }}</div>
                    <div><strong>Dirección:</strong> {{ $cliente->direccion }}</div>
                    <div><strong>Tipo:</strong> {{ ucfirst($cliente->tipo_cliente) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="h6">Resumen</div>
                    <div><strong>Total comprado:</strong> ${{ number_format($totalComprado,2) }}</div>
                    <div><strong>Saldo pendientes (créditos):</strong> ${{ number_format($saldoPendienteTotal,2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="h6">Período</div>
                    <form method="GET" action="{{ route('reportes.clientes.show', $cliente->id) }}">
                        <div class="d-flex gap-2">
                            <input type="date" name="desde" value="{{ $desde }}" class="form-control">
                            <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
                            <button class="btn btn-primary">Aplicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Gráfica de compras por mes --}}
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="clienteMesChart" height="70"></canvas>
            </div>
        </div>

        {{-- Historial de ventas --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Historial de ventas</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                        <tr><th>ID</th><th>Fecha</th><th>Total</th><th>Tipo</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                        @forelse($ventas as $v)
                            <tr>
                                <td>{{ $v->id_venta }}</td>
                                <td>{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}</td>
                                <td>${{ number_format($v->total,2) }}</td>
                                <td>{{ ucfirst($v->tipo_pago) }}</td>
                                <td>{{ ucfirst($v->estado) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No hay ventas en este periodo</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Créditos --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Créditos</h5>
                @if($creditos->count())
                    <table class="table table-striped">
                        <thead class="table-primary">
                        <tr><th>ID</th><th>Monto</th><th>Saldo</th><th>Inicio</th><th>Vencimiento</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                        @foreach($creditos as $cr)
                            <tr>
                                <td>{{ $cr->id_credito }}</td>
                                <td>${{ number_format($cr->monto_total,2) }}</td>
                                <td>${{ number_format($cr->saldo_pendiente,2) }}</td>
                                <td>{{ $cr->fecha_inicio }}</td>
                                <td>{{ $cr->fecha_vencimiento }}</td>
                                <td>{{ ucfirst($cr->estado) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">No hay créditos registrados para este cliente.</div>
                @endif
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('reportes.clientes.index') }}" class="btn btn-secondary">Volver</a>
            @if(class_exists(\Barryvdh\DomPDF\ServiceProvider::class))
                <a href="{{ route('reportes.clientes.export.pdf', ['search' => $cliente->nombre]) }}" class="btn btn-outline-secondary">Exportar PDF</a>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labelsMes = {!! json_encode($labelsMes ?? []) !!};
        const dataMes = {!! json_encode($dataMes ?? []) !!};

        const ctx = document.getElementById('clienteMesChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsMes,
                    datasets: [{
                        label: 'Compras por mes',
                        data: dataMes,
                        tension: 0.3,
                        fill: false,
                        borderWidth: 2
                    }]
                },
                options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
            });
        }
    </script>
@endsection
