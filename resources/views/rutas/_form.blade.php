@csrf

<div class="mb-3">
    <label class="form-label">Nombre de la ruta <span class="text-danger">*</span></label>
    <input type="text" name="nombre_ruta" class="form-control"
           value="{{ old('nombre_ruta', $ruta->nombre_ruta ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Día de la semana <span class="text-danger">*</span></label>
    <select name="dia_semana" class="form-select" required>
        @php
            $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
        @endphp
        @foreach($dias as $dia)
            <option value="{{ $dia }}" {{ old('dia_semana', $ruta->dia_semana ?? '') == $dia ? 'selected' : '' }}>
                {{ $dia }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Clientes asignados</label>
    <select name="clientes[]" class="form-select" multiple>
        @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}"
                {{ isset($ruta) && $ruta->clientes->contains($cliente->id) ? 'selected' : '' }}>
                {{ $cliente->nombre }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Mantén presionada la tecla Ctrl (Cmd en Mac) para seleccionar varios clientes.</small>
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> {{ isset($ruta) ? 'Actualizar' : 'Guardar' }}
    </button>
    <a href="{{ route('rutas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
