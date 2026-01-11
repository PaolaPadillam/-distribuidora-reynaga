<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Venta;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    public function index()
    {
        $entregas = Entrega::with('venta')->get();
        return view('entregas.index', compact('entregas'));
    }

    public function create()
    {
        $ventas = Venta::all();
        return view('entregas.create', compact('ventas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'fecha_entrega' => 'required|date',
            'estado' => 'required|string',
        ]);

        Entrega::create($request->all());
        return redirect()->route('entregas.index')->with('success', 'Entrega registrada correctamente');
    }

    public function edit(Entrega $entrega)
    {
        $ventas = Venta::all();
        return view('entregas.edit', compact('entrega', 'ventas'));
    }

    public function update(Request $request, Entrega $entrega)
    {
        $request->validate([
            'fecha_entrega' => 'required|date',
            'estado' => 'required|string',
        ]);

        $entrega->update($request->all());
        return redirect()->route('entregas.index')->with('success', 'Entrega actualizada correctamente');
    }

    public function destroy(Entrega $entrega)
    {
        $entrega->delete();
        return redirect()->route('entregas.index')->with('success', 'Entrega eliminada correctamente');
    }
}
