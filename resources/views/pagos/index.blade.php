@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Créditos activos</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
            <tr>
                <th>ID Crédito</th>
                <th>Cliente</th>
                <th>Venta</th>
                <th>Monto Total</th>
                <th>Saldo Pendiente</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($creditos as $credito)
                <tr>
                    <td>{{ $credito->id_credito }}</td>
                    <td>{{ $credito->cliente->nombre ?? 'Sin cliente' }}</td>
                    <td>#{{ $credito->venta->id_venta ?? '-' }}</td>
                    <td>${{ number_format($credito->monto_total, 2) }}</td>
                    <td>${{ number_format($credito->saldo_pendiente, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $credito->estado == 'activo' ? 'success' : 'secondary' }}">
                            {{ ucfirst($credito->estado) }}
                        </span>
                    </td>
                    <td>
                        @if($credito->estado == 'activo')
                            <a href="{{ route('pagos.create', $credito->id_credito) }}" class="btn btn-sm btn-primary">Registrar pago</a>
                        @else
                            <span class="text-muted">Liquidado</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
