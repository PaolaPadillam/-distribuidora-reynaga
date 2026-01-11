@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Registrar Nueva Venta</h2>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
                    @csrf

                    <!-- Cliente -->
                    <div class="mb-3">
                        <label for="id_cliente" class="form-label fw-semibold">Cliente</label>
                        <select name="id_cliente" id="id_cliente" class="form-select" required>
                            <option value="">Selecciona un cliente</option>
                            @foreach($clientes as $cliente)
                                <option
                                    value="{{ $cliente->id }}"
                                    data-tipo="{{ strtolower($cliente->tipo_cliente) }}"
                                    data-pago="{{ $cliente->maneja_credito ? 'credito' : 'contado' }}">
                                    {{ $cliente->nombre }} ({{ ucfirst($cliente->tipo_cliente) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="mb-3">
                        <label for="fecha" class="form-label fw-semibold">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                    </div>

                    <!-- Tipo de Pago (automático) -->
                    <div class="mb-3">
                        <label for="tipo_pago" class="form-label fw-semibold">Tipo de Pago</label>
                        <input type="text" id="tipo_pago_text" class="form-control" placeholder="Selecciona un cliente" readonly>
                        <input type="hidden" name="tipo_pago" id="tipo_pago">
                    </div>

                    <!-- Alerta dinámica -->
                    <div id="alertaTipoPago" style="display:none;"></div>

                    <!-- Campo de plazo (solo visible si el tipo de pago es crédito) -->
                    <div class="mb-3" id="plazoContainer" style="display: none;">
                        <label for="plazo_dias" class="form-label fw-semibold">Plazo del crédito (días)</label>
                        <input type="number" name="plazo_dias" id="plazo_dias" class="form-control" placeholder="Ejemplo: 30">
                    </div>

                    <hr>

                    <!-- Productos -->
                    <h5 class="fw-bold mb-3">Productos</h5>
                    <table class="table table-bordered align-middle" id="productosTable">
                        <thead class="table-light text-center">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="productosBody">
                        <tr>
                            <td>
                                <select name="productos[0][id_producto]" class="form-select producto-select" required>
                                    <option value="">Seleccione</option>
                                    @foreach($productos as $producto)
                                        <option
                                            value="{{ $producto->id_producto }}"
                                            data-mayoreo="{{ $producto->precio_mayoreo }}"
                                            data-menudeo="{{ $producto->precio_menudeo }}">
                                            {{ $producto->nombre_producto }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="productos[0][cantidad]" class="form-control cantidad" min="1" value="1" required>
                            </td>
                            <td>
                                <input type="number" name="productos[0][precio_unitario]" class="form-control precio_unitario" step="0.01" required>
                            </td>
                            <td class="subtotal text-center">$0.00</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm eliminar-fila">X</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-success btn-sm" id="agregarFila">
                            + Agregar producto
                        </button>
                    </div>

                    <div class="text-end mb-3">
                        <h5>Total: <span id="totalVenta" class="fw-bold text-success">$0.00</span></h5>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let filaIndex = 1;
            let tipoCliente = null;

            const alertaTipoPago = document.getElementById('alertaTipoPago');

            const actualizarTotales = () => {
                let total = 0;
                document.querySelectorAll('#productosBody tr').forEach(row => {
                    const cantidad = parseFloat(row.querySelector('.cantidad')?.value || 0);
                    const precio = parseFloat(row.querySelector('.precio_unitario')?.value || 0);
                    const subtotal = cantidad * precio;
                    row.querySelector('.subtotal').textContent = `$${subtotal.toFixed(2)}`;
                    total += subtotal;
                });
                document.getElementById('totalVenta').textContent = `$${total.toFixed(2)}`;
            };

            const actualizarPreciosPorCliente = () => {
                document.querySelectorAll('.producto-select').forEach(select => {
                    const fila = select.closest('tr');
                    const selected = select.selectedOptions[0];
                    if (!selected) return;
                    const precio = tipoCliente === 'mayoreo'
                        ? selected.getAttribute('data-mayoreo')
                        : selected.getAttribute('data-menudeo');
                    fila.querySelector('.precio_unitario').value = precio || 0;
                });
                actualizarTotales();
            };

            // Detectar cambio de cliente
            document.getElementById('id_cliente').addEventListener('change', e => {
                const selected = e.target.selectedOptions[0];
                tipoCliente = selected ? selected.getAttribute('data-tipo') : null;

                const tipoPago = selected ? selected.getAttribute('data-pago') : '';
                document.getElementById('tipo_pago').value = tipoPago;
                document.getElementById('tipo_pago_text').value = tipoPago ? tipoPago.charAt(0).toUpperCase() + tipoPago.slice(1) : '';

                // Mostrar u ocultar campo de plazo
                document.getElementById('plazoContainer').style.display = (tipoPago === 'credito') ? 'block' : 'none';

                // Mostrar alerta informativa
                if (tipoPago === 'credito') {
                    alertaTipoPago.style.display = 'block';
                    alertaTipoPago.className = 'alert alert-info';
                    alertaTipoPago.innerHTML = '<i class="bi bi-credit-card"></i> Esta venta será a <b>crédito</b>. Se generará un crédito activo automáticamente.';
                } else if (tipoPago === 'contado') {
                    alertaTipoPago.style.display = 'block';
                    alertaTipoPago.className = 'alert alert-success';
                    alertaTipoPago.innerHTML = '<i class="bi bi-cash-coin"></i> Esta venta será <b>de contado</b>. Se registrará el pago automáticamente.';
                } else {
                    alertaTipoPago.style.display = 'none';
                }

                actualizarPreciosPorCliente();
            });

            // Agregar nueva fila
            document.getElementById('agregarFila').addEventListener('click', () => {
                const nuevaFila = document.querySelector('#productosBody tr').cloneNode(true);
                nuevaFila.querySelectorAll('input, select').forEach(el => {
                    const name = el.name.replace(/\d+/, filaIndex);
                    el.name = name;
                    if (el.tagName === 'INPUT') el.value = '';
                });
                nuevaFila.querySelector('.subtotal').textContent = '$0.00';
                document.getElementById('productosBody').appendChild(nuevaFila);
                filaIndex++;
            });

            // Eliminar fila
            document.getElementById('productosBody').addEventListener('click', e => {
                if (e.target.classList.contains('eliminar-fila') && document.querySelectorAll('#productosBody tr').length > 1) {
                    e.target.closest('tr').remove();
                    actualizarTotales();
                }
            });

            // Calcular totales
            document.getElementById('productosBody').addEventListener('input', e => {
                if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio_unitario')) {
                    actualizarTotales();
                }
            });

            // Actualizar precio al seleccionar producto
            document.getElementById('productosBody').addEventListener('change', e => {
                if (e.target.classList.contains('producto-select')) {
                    const fila = e.target.closest('tr');
                    const selected = e.target.selectedOptions[0];
                    if (selected) {
                        const precio = tipoCliente === 'mayoreo'
                            ? selected.getAttribute('data-mayoreo')
                            : selected.getAttribute('data-menudeo');
                        fila.querySelector('.precio_unitario').value = precio || 0;
                        actualizarTotales();
                    }
                }
            });

            actualizarTotales();
        });
    </script>
@endsection
