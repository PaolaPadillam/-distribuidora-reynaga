@csrf

<div class="mb-3">
    <label class="form-label">Nombre <span class="text-danger">*</span></label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion ?? '') }}">
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email ?? '') }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tipo de cliente</label>
        <select name="tipo_cliente" class="form-select">
            <option value="menudeo" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'menudeo' ? 'selected' : '' }}>Menudeo</option>
            <option value="mayoreo" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'mayoreo' ? 'selected' : '' }}>Mayoreo</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="maneja_credito" value="1"
                {{ old('maneja_credito', $cliente->maneja_credito ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Maneja crédito</label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Límite de crédito</label>
    <input type="number" step="0.01" name="limite_credito" class="form-control"
           value="{{ old('limite_credito', $cliente->limite_credito ?? 0) }}">
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
