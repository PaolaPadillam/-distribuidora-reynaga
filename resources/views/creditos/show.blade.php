@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="fw-bold text-primary mb-4">Historial de Pagos del Crédito #{{ $credito->id_credito }}</h2>

        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body">
                <h5 class="fw-bold">Información del Crédito</h5>
                <p><strong>Cliente:</strong> {{ $credito->cliente->nombre }}</p>
                <p><strong>Venta relacionada:</strong> #{{ $credito->venta->id_venta }}</p>
                <p><strong>Monto total:</strong> ${{ number_format($credito->monto_total, 2) }}</p>
                <p><strong>Saldo pendiente:</strong> ${{ number_format($credito->saldo_pendiente, 2) }}</p>
                <p><strong>Estado:</strong>
                    <span class="badge
                    @if($credito->estado == 'activo') bg-success
                    @elseif($credito->estado == 'vencido') bg-danger
                    @else bg-secondary @endif">
                    {{ ucfirst($credito->estado) }}
                </span>
                </p>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Pagos registrados</h5>

                @if($credito->pagos->isEmpty())
                    <p class="text-muted">No hay pagos registrados para este crédito.</p>
                @else
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                        <tr class="text-center">
                            <th>Fecha de pago</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Observaciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($credito->pagos as $pago)
                            <tr>
                                <td>{{ $pago->fecha_pago }}</td>
                                <td class="text-success fw-bold">${{ number_format($pago->monto_pago, 2) }}</td>
                                <td>{{ ucfirst($pago->metodo_pago) }}</td>
                                <td>{{ $pago->observaciones ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="text-end mt-3">
                    <a href="{{ route('creditos.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
