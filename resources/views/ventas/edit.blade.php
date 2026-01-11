@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Editar Venta #{{ $venta->id_venta }}</h2>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('ventas.update', $venta->id_venta) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" id="cliente" class="form-control"
                                   value="{{ $venta->cliente->nombre }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                            <input type="text" id="tipo_pago" class="form-control"
                                   value="{{ ucfirst($venta->tipo_pago) }}" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" name="fecha" id="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ $venta->fecha }}" required>
                            @error('fecha')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="pendiente" {{ $venta->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado" {{ $venta->estado == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                <option value="cancelado" {{ $venta->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <h5 class="text-secondary mt-3">Resumen de productos vendidos</h5>
                    <table class="table table-striped table-bordered mt-2">
                        <thead class="table-light">
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
                        <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Total:</td>
                            <td>${{ number_format($venta->total, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>

                    <div class="text-end mt-4">
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
