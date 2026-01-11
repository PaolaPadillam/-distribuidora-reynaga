@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Editar Ruta</h2>

        <form action="{{ route('rutas.update', $ruta->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre_ruta" class="form-label">Nombre de la Ruta</label>
                <input type="text" class="form-control" id="nombre_ruta" name="nombre_ruta" value="{{ $ruta->nombre_ruta }}" required>
            </div>

            <div class="mb-3">
                <label for="dia_semana" class="form-label">Día de la Semana</label>
                <select name="dia_semana" id="dia_semana" class="form-select" required>
                    @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                        <option value="{{ $dia }}" {{ $ruta->dia_semana == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="clientes" class="form-label">Clientes</label>
                <div class="d-flex flex-wrap">
                    @foreach($clientes as $cliente)
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" name="clientes[]" value="{{ $cliente->id }}" id="cliente{{ $cliente->id }}" {{ in_array($cliente->id, $rutaClientes) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cliente{{ $cliente->id }}">{{ $cliente->nombre }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Color de la Ruta</label>
                <input type="color" name="color" id="color" class="form-control form-control-color" value="{{ $ruta->color }}" title="Elige un color">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Ruta</button>
        </form>
    </div>
@endsection
