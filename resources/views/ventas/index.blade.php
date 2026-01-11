@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Gestión de Ventas</h2>

        <!-- Buscador -->
        <form action="{{ route('ventas.index') }}" method="GET" class="d-flex mb-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar por cliente" value="{{ request('search') }}">
            <button class="btn btn-primary">Buscar</button>
        </form>


        <!-- Botón Crear -->
        <div class="mb-3">
            <a href="{{ route('ventas.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nueva Venta
            </a>
        </div>

        <!-- Tabla de Ventas -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Tipo de Pago</th>
                        <th>Estado</th> <!-- Nueva columna -->
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ventas as $venta)
                        <tr>
                            <td>{{ $venta->id_venta }}</td>
                            <td>{{ $venta->cliente->nombre ?? 'Sin cliente' }}</td>
                            <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                            <td>${{ number_format($venta->total, 2) }}</td>
                            <td>
            <span class="badge {{ $venta->tipo_pago === 'credito' ? 'bg-warning text-dark' : 'bg-success' }}">
                {{ ucfirst($venta->tipo_pago) }}
            </span>
                            </td>
                            <td>
            <span class="badge
                @if($venta->estado === 'pendiente') bg-secondary
                @elseif($venta->estado === 'pagado') bg-success
                @else bg-danger
                @endif">
                {{ ucfirst($venta->estado) }}
            </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('ventas.show', $venta->id_venta) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('ventas.edit', $venta->id_venta) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('ventas.destroy', $venta->id_venta) }}" method="POST" class="d-inline delete-form">
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
                            <td colspan="6" class="text-center text-muted">No hay ventas registradas</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

                <div class="mt-3">
                    {{ $ventas->links() }}
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
                        title: '¿Eliminar venta?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
@endsection
