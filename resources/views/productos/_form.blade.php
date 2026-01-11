@csrf

<div class="mb-3">
    <label class="form-label">Nombre del producto <span class="text-danger">*</span></label>
    <input type="text" name="nombre_producto" class="form-control" value="{{ old('nombre_producto', $producto->nombre_producto ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $producto->descripcion ?? '') }}">
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Precio mayoreo</label>
        <input type="number" step="0.01" name="precio_mayoreo" class="form-control" value="{{ old('precio_mayoreo', $producto->precio_mayoreo ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Precio menudeo</label>
        <input type="number" step="0.01" name="precio_menudeo" class="form-control" value="{{ old('precio_menudeo', $producto->precio_menudeo ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock ?? 0) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Unidad</label>
        <input type="text" name="unidad" class="form-control" value="{{ old('unidad', $producto->unidad ?? '') }}" placeholder="Ej. kg, litro, pieza, caja">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Proveedor</label>
        <select name="proveedor_id" class="form-select" required>
            <option value="">-- Selecciona un proveedor --</option>
            @foreach($proveedores as $p)
                <option value="{{ $p->id_proveedor }}"
                    {{ old('proveedor_id', $producto->proveedor_id ?? '') == $p->id_proveedor ? 'selected' : '' }}>
                    {{ $p->nombre }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div class="mb-3">
    <label class="form-label">Fecha de caducidad</label>
    <input type="date" name="fecha_caducidad" class="form-control" value="{{ old('fecha_caducidad', isset($producto->fecha_caducidad) ? $producto->fecha_caducidad->format('Y-m-d') : '') }}">
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
