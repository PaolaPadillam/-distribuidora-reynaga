@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="fw-bold text-primary mb-4">Editar Crédito</h2>

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
                <form action="{{ route('creditos.update', $credito->id_credito) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select name="id_cliente" id="id_cliente" class="form-select" required>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}" {{ $credito->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_venta" class="form-label">Venta Asociada</label>
                            <select name="id_venta" id="id_venta" class="form-select" required>
                                @foreach($ventas as $venta)
                                    <option value="{{ $venta->id_venta }}" {{ $credito->id_venta == $venta->id_venta ? 'selected' : '' }}>
                                        Venta #{{ $venta->id_venta }} - ${{ number_format($venta->total, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="monto_total" class="form-label">Monto Total</label>
                            <input type="number" step="0.01" name="monto_total" id="monto_total" class="form-control" value="{{ $credito->monto_total }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="saldo_pendiente" class="form-label">Saldo Pendiente</label>
                            <input type="number" step="0.01" name="saldo_pendiente" id="saldo_pendiente" class="form-control" value="{{ $credito->saldo_pendiente }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $credito->fecha_inicio }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" value="{{ $credito->fecha_vencimiento }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado del Crédito</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="activo" {{ $credito->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="liquidado" {{ $credito->estado == 'liquidado' ? 'selected' : '' }}>Liquidado</option>
                            <option value="vencido" {{ $credito->estado == 'vencido' ? 'selected' : '' }}>Vencido</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('creditos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar Crédito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
