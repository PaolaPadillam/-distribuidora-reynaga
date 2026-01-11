@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Agregar Registro: {{ $ruta->nombre_ruta }}</h2>

        <form action="{{ route('ruta_registros.store', $ruta->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $fecha }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-select" required>
                    <option value="Cumplida">Cumplida</option>
                    <option value="Movida">Movida</option>
                    <option value="Incompleta">Incompleta</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Observaciones:</label>
                <textarea name="observaciones" class="form-control" maxlength="200"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('ruta_registros.index', $ruta->id) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
