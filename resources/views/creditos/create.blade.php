@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="fw-bold text-primary mb-4">Registrar Nuevo Crédito</h2>

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('creditos.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select name="id_cliente" id="id_cliente" class="form-select" required>
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_venta" class="form-label">Venta asociada</label>
                            <select name="id_venta" id="id_venta" class="form-select" required>
                                <option value="">Seleccione una venta</option>
                                @foreach($ventas as $venta)
                                    <option value="{{ $venta->id_venta }}">Venta #{{ $venta->id_venta }} - ${{ number_format($venta->total, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="monto_total" class="form-label">Monto Total</label>
                            <input type="number" step="0.01" name="monto_total" id="monto_total" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="saldo_pendiente" class="form-label">Saldo Pendiente</label>
                            <input type="number" step="0.01" name="saldo_pendiente" id="saldo_pendiente" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('creditos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Crédito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
