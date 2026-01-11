<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('telefono', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $clientes = $query->orderBy('nombre')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }


    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('clientes.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'tipo_cliente' => 'required|in:mayoreo,menudeo',
            'maneja_credito' => 'nullable|boolean',
            'limite_credito' => 'nullable|numeric',
        ]);

        $data['maneja_credito'] = $request->has('maneja_credito');
        $data['saldo_actual'] = $data['saldo_actual'] ?? 0.00;
        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success','Cliente creado.');
    }

    public function edit(Cliente $cliente): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'tipo_cliente' => 'required|in:mayoreo,menudeo',
            'maneja_credito' => 'nullable|boolean',
            'limite_credito' => 'nullable|numeric',
        ]);

        $data['maneja_credito'] = $request->has('maneja_credito');
        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success','Cliente actualizado.');
    }

    public function destroy(Cliente $cliente): \Illuminate\Http\RedirectResponse
    {
        // puedes validar que no tenga ventas relacionadas antes de borrar
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success','Cliente eliminado.');
    }
}
