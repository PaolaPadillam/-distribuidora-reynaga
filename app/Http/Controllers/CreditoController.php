<?php

namespace App\Http\Controllers;

use App\Models\Credito;
use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function index()
    {
        $creditos = Credito::with(['venta', 'cliente'])->get();
        return view('creditos.index', compact('creditos'));
    }

    public function create()
    {
        $ventas = Venta::all();
        $clientes = Cliente::all();
        return view('creditos.create', compact('ventas', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_venta' => 'required|exists:ventas,id_venta',
            'monto_total' => 'required|numeric|min:0',
            'saldo_pendiente' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,liquidado,vencido',
        ]);

        Credito::create($request->all());
        return redirect()->route('creditos.index')->with('success', 'Crédito registrado correctamente');
    }

    public function edit(Credito $credito)
    {
        $ventas = Venta::all();
        $clientes = Cliente::all();
        return view('creditos.edit', compact('credito', 'ventas', 'clientes'));
    }

    public function update(Request $request, Credito $credito)
    {
        $request->validate([
            'monto_total' => 'required|numeric|min:0',
            'saldo_pendiente' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,liquidado,vencido',
        ]);

        $credito->update($request->all());
        return redirect()->route('creditos.index')->with('success', 'Crédito actualizado correctamente');
    }

    public function destroy(Credito $credito)
    {
        $credito->delete();
        return redirect()->route('creditos.index')->with('success', 'Crédito eliminado correctamente');
    }

    public function show($id)
    {
        $credito = \App\Models\Credito::with(['cliente', 'venta', 'pagos'])->findOrFail($id);
        return view('creditos.show', compact('credito'));
    }


}
