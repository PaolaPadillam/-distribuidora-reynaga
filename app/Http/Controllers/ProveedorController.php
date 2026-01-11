<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('telefono', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $proveedores = $query->orderBy('nombre')->paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        Proveedor::create($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'direccion' => 'nullable|max:200',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100'
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')->with('success','Proveedor actualizado correctamente.');
    }


    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success','Proveedor eliminado.');
    }
}
