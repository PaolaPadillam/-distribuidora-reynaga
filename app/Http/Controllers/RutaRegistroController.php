<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\RutaRegistro;
use Illuminate\Http\Request;

class RutaRegistroController extends Controller
{
    public function index($ruta_id)
    {
        $ruta = Ruta::findOrFail($ruta_id);
        $registros = RutaRegistro::where('ruta_id', $ruta_id)
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return view('rutas_registros.index', compact('ruta', 'registros'));
    }

    public function create(Request $request, $ruta_id)
    {
        $ruta = Ruta::findOrFail($ruta_id);
        $fecha = $request->query('fecha', date('Y-m-d')); // Si no hay fecha, hoy
        return view('rutas_registros.create', compact('ruta', 'fecha'));
    }

    public function store(Request $request, $ruta_id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:Cumplida,Movida,Incompleta',
            'observaciones' => 'nullable|string|max:200',
        ]);

        RutaRegistro::create([
            'ruta_id' => $ruta_id,
            'fecha' => $request->fecha,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('ruta_registros.index', $ruta_id)
            ->with('success', 'Registro guardado correctamente.');
    }

    public function edit($ruta_id, RutaRegistro $registro)
    {
        $ruta = Ruta::findOrFail($ruta_id);
        return view('rutas_registros.edit', compact('ruta', 'registro'));
    }

    public function update(Request $request, $ruta_id, RutaRegistro $registro)
    {
        $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:Cumplida,Movida,Incompleta',
            'observaciones' => 'nullable|string|max:200',
        ]);

        $registro->update($request->all());

        return redirect()->route('ruta_registros.index', $ruta_id)
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($ruta_id, RutaRegistro $registro)
    {
        $registro->delete();
        return redirect()->route('ruta_registros.index', $ruta_id)
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function calendario($ruta_id)
    {
        $ruta = Ruta::findOrFail($ruta_id);
        $registros = $ruta->registros()->get(); // relación en el modelo Ruta

        // Convertimos los registros a formato que FullCalendar entiende
        $eventos = $registros->map(function($registro){
            return [
                'title' => $registro->estado,
                'start' => $registro->fecha,
                'color' => match($registro->estado) {
                    'Cumplida' => 'green',
                    'Movida' => 'orange',
                    'Incompleta' => 'red',
                    default => 'blue',
                },
                'url' => route('ruta_registros.edit', $registro->id), // clic para editar
            ];
        });

        return view('rutas.calendario', compact('ruta', 'eventos'));
    }

    public function calendarioGeneral()
    {
        $rutas = \App\Models\Ruta::with('registros')->get();

        $eventos = [];

        foreach ($rutas as $ruta) {
            foreach ($ruta->registros as $registro) {
                $eventos[] = [
                    'title' => $ruta->nombre_ruta . ' - ' . $registro->estado,
                    'start' => $registro->fecha,
                    'color' => match($registro->estado) {
                        'Cumplida' => 'green',
                        'Movida' => 'orange',
                        'Incompleta' => 'red',
                        default => 'blue',
                    },
                ];
            }
        }

        return view('rutas.calendario_general', compact('eventos'));
    }


}
