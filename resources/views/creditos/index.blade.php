@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="fw-bold text-primary mb-4">Gestión de Créditos</h2>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Tabla de créditos -->
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Venta</th>
                        <th>Monto Total</th>
                        <th>Saldo Pendiente</th>
                        <th>Estado</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($creditos as $credito)
                        <tr>
                            <td>{{ $credito->id_credito }}</td>
                            <td>{{ $credito->cliente->nombre ?? 'Sin cliente' }}</td>
                            <td>{{ $credito->venta->id_venta ?? 'N/A' }}</td>
                            <td>${{ number_format($credito->monto_total, 2) }}</td>
                            <td>${{ number_format($credito->saldo_pendiente, 2) }}</td>
                            <td>
                                    <span class="badge
                                        @if($credito->estado == 'activo') bg-success
                                        @elseif($credito->estado == 'vencido') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($credito->estado) }}
                                    </span>
                            </td>
                            <td>{{ $credito->fecha_inicio }}</td>
                            <td>{{ $credito->fecha_vencimiento }}</td>
                            <td>
                                @if($credito->estado === 'activo' && $credito->saldo_pendiente > 0)
                                    <a href="{{ route('pagos.create', ['id_credito' => $credito->id_credito]) }}"
                                       class="btn btn-success btn-sm">
                                        <i class="bi bi-cash-coin"></i> Registrar Pago
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Liquidado</span>
                                @endif
                                    <!-- Botón Ver Historial de Pagos -->
                                    <a href="{{ route('creditos.show', $credito->id_credito) }}"
                                       class="btn btn-info btn-sm text-black">
                                        <i class="bi bi-eye"></i> Ver pagos
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No hay créditos registrados
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
