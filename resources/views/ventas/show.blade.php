@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Detalle de Venta</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3 text-secondary">Información general</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>ID Venta</th>
                        <td>{{ $venta->id_venta }}</td>
                    </tr>
                    <tr>
                        <th>Cliente</th>
                        <td>{{ $venta->cliente->nombre ?? 'Sin cliente' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha</th>
                        <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>${{ number_format($venta->total, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Pago</th>
                        <td>
                        <span class="badge {{ $venta->tipo_pago === 'credito' ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ ucfirst($venta->tipo_pago) }}
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                        <span class="badge
                            @if($venta->estado === 'pendiente') bg-secondary
                            @elseif($venta->estado === 'pagado') bg-success
                            @else bg-danger
                            @endif">
                            {{ ucfirst($venta->estado) }}
                        </span>
                        </td>
                    </tr>
                </table>

                @if($venta->detalles->count() > 0)
                    <h5 class="mt-4 text-secondary">Productos vendidos</h5>
                    <table class="table table-striped table-hover mt-2">
                        <thead class="table-primary">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre_producto ?? 'N/A' }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td>${{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mt-3">
                        No hay productos registrados en esta venta.
                    </div>
                @endif

                <div class="text-end mt-4">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
