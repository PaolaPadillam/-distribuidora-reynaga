@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary fw-bold">Seguimiento Diario - {{ $ruta->nombre_ruta }}</h2>
            <a href="{{ route('ruta_registros.create', $ruta->id) }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Registro
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover">
                <thead class="table-primary text-center">
                <tr>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody class="text-center">
                @forelse($registros as $registro)
                    <tr>
                        <td>{{ $registro->fecha }}</td>
                        <td>{{ $registro->estado }}</td>
                        <td>{{ $registro->observaciones ?? '—' }}</td>
                        <td>
                            <a href="{{ route('ruta_registros.edit', [$ruta->id, $registro->id]) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('ruta_registros.destroy', [$ruta->id, $registro->id]) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">No hay registros para esta ruta.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $registros->links() }}
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                const button = form.querySelector('.delete-btn');
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar registro?',
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
