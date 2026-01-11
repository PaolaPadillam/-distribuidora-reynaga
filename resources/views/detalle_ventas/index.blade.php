@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Detalle de Venta #{{ $venta->id }}</h2>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p><strong>Cliente:</strong> {{ $venta->cliente->nombre }}</p>
                <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
                <p><strong>Tipo de pago:</strong>
                    <span class="badge {{ $venta->tipo_pago == 'credito' ? 'bg-warning text-dark' : 'bg-success' }}">
                    {{ ucfirst($venta->tipo_pago) }}
                </span>
                </p>
            </div>
        </div>

        <!-- Formulario para agregar productos -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="text-secondary mb-3">Agregar Producto</h5>

                <form action="{{ route('detalle_ventas.store', $venta->id) }}" method="POST" class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-5">
                        <label for="id_producto" class="form-label">Producto</label>
                        <select name="id_producto" id="id_producto" class="form-select" required>
                            <option value="">Selecciona un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id_producto }}">{{ $producto->nombre_producto }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-2">
                        <label for="precio_unitario" class="form-label">Precio</label>
                        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" class="form-control" required>
                    </div>

                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de productos agregados -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="text-secondary mb-3">Productos en esta venta</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($venta->detalleVentas as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre_producto }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>${{ number_format($detalle->subtotal, 2) }}</td>
                            <td>
                                <form action="{{ route('detalle_ventas.destroy', $detalle->id_detalle) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay productos en esta venta.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="text-end mt-3">
                    <h5>Total:
                        <span class="fw-bold text-success">
                        ${{ number_format($venta->total, 2) }}
                    </span>
                    </h5>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Regresar a Ventas</a>
                </div>
            </div>
        </div>
    </div>
@endsection
