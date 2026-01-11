@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary fw-bold">👥 Clientes</h2>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Nuevo Cliente
            </a>
        </div>

        <!-- Barra de búsqueda -->
        <form method="GET" action="{{ route('clientes.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, teléfono o email..." value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tabla -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Crédito</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->telefono ?? '—' }}</td>
                            <td>{{ $cliente->direccion ?? '—' }}</td>
                            <td>{{ $cliente->email ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $cliente->tipo_cliente == 'mayoreo' ? 'bg-success' : 'bg-info' }}">
                                    {{ ucfirst($cliente->tipo_cliente) }}
                                </span>
                            </td>
                            <td>
                                @if($cliente->maneja_credito)
                                    💳 {{ number_format($cliente->limite_credito, 2) }}
                                @else
                                    ❌
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline delete-form">
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
                            <td colspan="6" class="text-muted">No hay clientes registrados</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $clientes->links() }}
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
                        title: '¿Eliminar cliente?',
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
