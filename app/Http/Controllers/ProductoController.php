<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $productos = Producto::with('proveedor')
            ->when($search, function($query, $search){
                return $query->where('nombre_producto', 'like', "%$search%")
                    ->orWhere('descripcion', 'like', "%$search%");
            })
            ->paginate(10);

        return view('productos.index', compact('productos', 'search'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('productos.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_producto' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'precio_mayoreo' => 'required|numeric',
            'precio_menudeo' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'unidad' => 'nullable|string|max:20',
            'proveedor_id' => 'required|exists:proveedores,id_proveedor',
            'fecha_caducidad' => 'nullable|date',
        ]);

        Producto::create($data);
        return redirect()->route('productos.index')->with('success', 'Producto agregado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $proveedores = Proveedor::all();
        return view('productos.edit', compact('producto', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre_producto' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'precio_mayoreo' => 'required|numeric',
            'precio_menudeo' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'unidad' => 'nullable|string|max:20',
            'proveedor_id' => 'required|exists:proveedores,id_proveedor',
            'fecha_caducidad' => 'nullable|date',
        ]);

        $producto->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }


}
