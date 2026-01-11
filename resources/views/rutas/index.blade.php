@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary fw-bold">Rutas</h2>
            <div>
                <a href="{{ route('rutas.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva Ruta
                </a>
                <a href="{{ route('rutas.calendario') }}" class="btn btn-info">
                    <i class="bi bi-calendar3"></i> Calendario
                </a>
            </div>
        </div>


        <table class="table table-striped table-hover">
            <thead class="table-primary text-center">
            <tr>
                <th>Nombre</th>
                <th>Día</th>
                <th>Clientes</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody class="text-center">
            @forelse($rutas as $ruta)
                <tr>
                    <td>{{ $ruta->nombre_ruta }}</td>
                    <td>{{ $ruta->dia_semana }}</td>
                    <td>
                        @foreach($ruta->clientes as $cliente)
                            {{ $cliente->nombre }}<br>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                        <form action="{{ route('rutas.destroy', $ruta) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                        <a href="{{ route('ruta_registros.index', $ruta) }}" class="btn btn-info btn-sm mt-1">
                            <i class="bi bi-calendar-check"></i> Seguimiento diario
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-muted">No hay rutas registradas</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const form = this.closest('form');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta ruta será eliminada permanentemente',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
