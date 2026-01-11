@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Gestión de Proveedores</h2>

        <!-- Buscador -->
        <form action="{{ route('proveedores.index') }}" method="GET" class="d-flex mb-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar proveedor..." value="{{ request('search') }}">
            <button class="btn btn-primary">Buscar</button>
        </form>

        <a href="{{ route('proveedores.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-truck"></i> Nuevo Proveedor
        </a>

        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                    <tr>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($proveedores as $proveedor)
                        <tr>
                            <td>{{ $proveedor->nombre }}</td>
                            <td>{{ $proveedor->direccion ?? '—' }}</td>
                            <td>{{ $proveedor->telefono ?? '—' }}</td>
                            <td>{{ $proveedor->email ?? '—' }}</td>
                            <td>
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No hay proveedores registrados</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $proveedores->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                const button = form.querySelector('.delete-btn');
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Eliminar proveedor?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
