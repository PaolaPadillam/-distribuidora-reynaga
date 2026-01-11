@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary fw-bold">Inventario</h2>
            <a href="{{ route('productos.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        </div>

        <form action="{{ route('productos.index') }}" method="GET" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Buscar producto..." value="{{ $search }}">
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover">
                <thead class="table-primary text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio mayoreo</th>
                    <th>Precio menudeo</th>
                    <th>Stock</th>
                    <th>Unidad</th>
                    <th>Proveedor</th>
                    <th>Caducidad</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody class="text-center">
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre_producto }}</td>
                        <td>{{ $producto->descripcion ?? '—' }}</td>
                        <td>{{ number_format($producto->precio_mayoreo,2) }}</td>
                        <td>{{ number_format($producto->precio_menudeo,2) }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->unidad ?? '—' }}</td>
                        <td>{{ $producto->proveedor->nombre ?? '—' }}</td>
                        <td>{{ $producto->fecha_caducidad ? $producto->fecha_caducidad->format('Y-m-d') : '—' }}</td>
                        <td>
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted">No hay productos registrados</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $productos->links() }}
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                const button = form.querySelector('.delete-btn');
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar producto?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // envía el formulario tradicional
                        }
                    });
                });
            });
        });
    </script>
@endsection
