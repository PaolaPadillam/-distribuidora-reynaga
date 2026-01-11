@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Calendario de Rutas</h2>

        <div id="calendar"></div>
    </div>

    <!-- Modal para crear registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formRegistro" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Seguimiento Diario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ruta_id" class="form-label">Ruta</label>
                        <select name="ruta_id" id="ruta_id" class="form-select" required>
                            <option value="">Selecciona una ruta</option>
                            @foreach(\App\Models\Ruta::all() as $ruta)
                                <option value="{{ $ruta->id }}">{{ $ruta->nombre_ruta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="Cumplida">Cumplida</option>
                            <option value="Movida">Movida</option>
                            <option value="Incompleta">Incompleta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const modal = new bootstrap.Modal(document.getElementById('modalRegistro'));
            const form = document.getElementById('formRegistro');
            const inputFecha = document.getElementById('fecha');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                editable: true,
                selectable: true,
                events: '{{ route('rutas.eventos') }}',

                // ✅ Crear evento al hacer clic en una fecha
                dateClick: function(info) {
                    inputFecha.value = info.dateStr;
                    modal.show();
                },

                // ✅ Arrastrar evento para cambiar fecha
                eventDrop: function(info) {
                    fetch('{{ url('rutas/actualizar-fecha') }}/' + info.event.id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ fecha: info.event.startStr })
                    }).then(() => {
                        Swal.fire('Actualizado', 'La fecha fue cambiada correctamente.', 'success');
                    });
                },

                // ✅ Mostrar detalles al hacer clic en evento
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const registroId = info.event.id;

                    fetch(`/rutas/${registroId}/detalle`)
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: `<strong>${data.nombre_ruta}</strong>`,
                                html: `
                                    <p><b>Día de la semana:</b> ${data.dia_semana}</p>
                                    <p><b>Estado:</b> ${data.estado ?? 'N/A'}</p>
                                    <p><b>Observaciones:</b> ${data.observaciones ?? 'Sin observaciones'}</p>
                                `,
                                icon: 'info',
                                confirmButtonText: 'Cerrar',
                                confirmButtonColor: data.color ?? '#3085d6'
                            });
                        })
                        .catch(error => {
                            console.error('Error al obtener detalles de la ruta:', error);
                            Swal.fire('Error', 'No se pudieron cargar los detalles de la ruta.', 'error');
                        });
                }
            });

            calendar.render();

            // ✅ Enviar formulario modal
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const data = {
                    ruta_id: form.ruta_id.value,
                    fecha: form.fecha.value,
                    estado: form.estado.value,
                    observaciones: form.observaciones.value,
                };

                fetch('{{ route('rutas.crearDesdeCalendario') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                    .then(res => res.json())
                    .then(() => {
                        modal.hide();
                        Swal.fire('Guardado', 'Seguimiento diario agregado correctamente.', 'success');
                        calendar.refetchEvents();
                    });
            });
        });
    </script>
@endsection
