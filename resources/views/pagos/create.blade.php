@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary fw-bold mb-4">Registrar Pago</h2>

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                {{-- 🧾 Información del crédito --}}
                <div class="bg-light p-3 rounded-3 mb-4 border-start border-4 border-primary">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="bi bi-credit-card-2-front me-2"></i>Información del Crédito
                    </h5>

                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Crédito:</strong> #{{ $credito->id_credito }}</p>
                            <p class="mb-1"><strong>Cliente:</strong> {{ $credito->cliente->nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Monto Total:</strong> ${{ number_format($credito->monto_total, 2) }}</p>
                            <p class="mb-1"><strong>Saldo Pendiente:</strong>
                                <span class="text-danger fw-bold">${{ number_format($credito->saldo_pendiente, 2) }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Fecha Inicio:</strong> {{ $credito->fecha_inicio }}</p>
                            <p class="mb-1"><strong>Vence:</strong> {{ $credito->fecha_vencimiento }}</p>
                        </div>
                    </div>
                </div>

                {{-- 💰 Formulario de pago --}}
                <form action="{{ route('pagos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_credito" value="{{ $credito->id_credito }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fecha_pago" class="form-label fw-semibold">Fecha de pago</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="form-control shadow-sm" required>
                        </div>

                        <div class="col-md-6">
                            <label for="monto_pago" class="form-label fw-semibold">Monto del pago</label>
                            <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control shadow-sm" required>
                        </div>

                        <div class="col-md-6">
                            <label for="metodo_pago" class="form-label fw-semibold">Método de pago</label>
                            <select name="metodo_pago" id="metodo_pago" class="form-select shadow-sm" required>
                                <option value="">Selecciona método...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control shadow-sm" rows="2" placeholder="Ejemplo: abono parcial, transferencia bancaria, etc."></textarea>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('creditos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-save"></i> Guardar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
