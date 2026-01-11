<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Credito;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Mostrar formulario para registrar un nuevo pago
     */
    public function create(Request $request)
    {
        // Verifica si viene el id del crédito en la URL
        $id_credito = $request->query('id_credito');

        if (!$id_credito) {
            return redirect()->route('creditos.index')->with('error', 'No se especificó un crédito válido.');
        }

        // Cargar el crédito junto con el cliente relacionado
        $credito = Credito::with('cliente')->findOrFail($id_credito);

        return view('pagos.create', compact('credito'));
    }


    /**
     * Guardar un nuevo pago y actualizar automáticamente el crédito
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_credito' => 'required|exists:creditos,id_credito',
            'fecha_pago' => 'required|date',
            'monto_pago' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta',
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Buscar el crédito correspondiente
        $credito = Credito::findOrFail($validated['id_credito']);

        // Validar que aún haya saldo pendiente
        if ($credito->saldo_pendiente <= 0) {
            return redirect()
                ->route('creditos.index')
                ->with('error', 'El crédito ya está liquidado.');
        }

        // Crear el pago
        Pago::create($validated);

        // Restar el monto pagado al saldo pendiente
        $nuevoSaldo = $credito->saldo_pendiente - $validated['monto_pago'];
        $credito->saldo_pendiente = max($nuevoSaldo, 0);

        // Actualizar estado del crédito
        if ($credito->saldo_pendiente == 0) {
            $credito->estado = 'liquidado';
        }

        // Si ya venció y aún no está liquidado, lo marcamos como vencido
        if ($credito->saldo_pendiente > 0 && Carbon::now()->gt(Carbon::parse($credito->fecha_vencimiento))) {
            $credito->estado = 'vencido';
        }

        $credito->save();

        return redirect()
            ->route('creditos.index')
            ->with('success', 'Pago registrado correctamente y crédito actualizado.');
    }
}
