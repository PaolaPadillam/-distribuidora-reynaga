<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta;
use App\Models\RutaRegistro;
use App\Models\Cliente;


class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::all();
        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        $clientes = Cliente::all(); // obtenemos todos los clientes
        return view('rutas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_ruta' => 'required|string|max:100',
            'dia_semana' => 'required|string',
            'color' => 'required|string',
        ]);

        Ruta::create($request->all());
        return redirect()->route('rutas.index')->with('success', 'Ruta creada correctamente.');
    }

    public function edit($id)
    {
        $ruta = Ruta::findOrFail($id);
        $clientes = \App\Models\Cliente::all(); // Todos los clientes
        $rutaClientes = $ruta->clientes->pluck('id')->toArray(); // IDs de los clientes asociados a la ruta

        return view('rutas.edit', compact('ruta', 'clientes', 'rutaClientes'));
    }


    public function update(Request $request, $id)
    {
        $ruta = Ruta::findOrFail($id);
        $request->validate([
            'nombre_ruta' => 'required|string|max:100',
            'dia_semana' => 'required|string',
            'color' => 'required|string',
        ]);

        // Actualiza la relación con clientes
        if ($request->has('clientes')) {
            $ruta->clientes()->sync($request->clientes);
        }

        $ruta->update($request->all());
        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->delete();
        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente.');
    }

    /** ✅ CALENDARIO GENERAL INTERACTIVO **/
    public function calendario()
    {
        return view('rutas.calendario');
    }

    /** ✅ EVENTOS DEL CALENDARIO (JSON) **/
    public function eventos()
    {
        $rutas = Ruta::with('registros')->get();
        $eventos = [];

        foreach ($rutas as $ruta) {
            foreach ($ruta->registros as $registro) {
                $eventos[] = [
                    'id' => $registro->id,
                    'title' => $ruta->nombre_ruta,
                    'start' => $registro->fecha,
                    'end' => $registro->fecha,
                    'color' => $ruta->color ?? '#3498db',
                ];
            }
        }

        return response()->json($eventos);
    }


    /** ✅ ACTUALIZAR FECHA AL MOVER EVENTO **/
    public function actualizarFecha(Request $request, $id)
    {
        $registro = RutaRegistro::findOrFail($id);
        $registro->update(['fecha' => $request->fecha]);
        return response()->json(['success' => true]);
    }

    /** ✅ CREAR NUEVO EVENTO DESDE EL CALENDARIO **/
    public function crearDesdeCalendario(Request $request)
    {
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'fecha' => 'required|date',
            'estado' => 'required|string',
            'observaciones' => 'nullable|string|max:200',
        ]);

        RutaRegistro::create([
            'ruta_id' => $request->ruta_id,
            'fecha' => $request->fecha,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones ?? 'Creada desde calendario',
        ]);

        return response()->json(['success' => true]);
    }

    public function detalle($id)
    {
        $registro = \App\Models\RutaRegistro::with('ruta')->findOrFail($id);

        return response()->json([
            'nombre_ruta' => $registro->ruta->nombre_ruta,
            'dia_semana' => $registro->ruta->dia_semana,
            'estado' => $registro->estado,
            'observaciones' => $registro->observaciones,
            'color' => $registro->ruta->color,
        ]);
    }


}
