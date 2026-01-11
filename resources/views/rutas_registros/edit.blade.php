@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Editar Registro: {{ $ruta->nombre_ruta }}</h2>

        <form action="{{ route('ruta_registros.update', [$ruta->id, $registro->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" class="form-control" value="{{ $registro->fecha }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-select" required>
                    <option value="Cumplida" @selected($registro->estado=='Cumplida')>Cumplida</option>
                    <option value="Movida" @selected($registro->estado=='Movida')>Movida</option>
                    <option value="Incompleta" @selected($registro->estado=='Incompleta')>Incompleta</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Observaciones:</label>
                <textarea name="observaciones" class="form-control" maxlength="200">{{ $registro->observaciones }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('ruta_registros.index', $ruta->id) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
