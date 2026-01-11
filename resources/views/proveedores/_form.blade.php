@csrf

<div class="mb-3">
    <label class="form-label">Nombre <span class="text-danger">*</span></label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $proveedor->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion ?? '') }}">
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $proveedor->email ?? '') }}">
    </div>
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
