<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function index($venta_id)
    {
        $venta = Venta::with('cliente', 'detalleVentas.producto')->findOrFail($venta_id);
        $productos = Producto::all();
        return view('detalle_ventas.index', compact('venta', 'productos'));
    }

    public function store(Request $request, $venta_id)
    {
        $venta = Venta::findOrFail($venta_id);

        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->cantidad * $request->precio_unitario;

        DetalleVenta::create([
            'id_venta' => $venta_id,
            'id_producto' => $request->id_producto,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
            'subtotal' => $subtotal,
        ]);

        // Actualizar total de la venta
        $venta->total = $venta->detalleVentas()->sum('subtotal');
        $venta->save();

        return redirect()->route('detalle_ventas.index', $venta_id)->with('success', 'Producto agregado.');
    }

    public function destroy($id)
    {
        $detalle = DetalleVenta::findOrFail($id);
        $venta = Venta::findOrFail($detalle->id_venta);

        $detalle->delete();

        // Recalcular total de la venta
        $venta->total = $venta->detalleVentas()->sum('subtotal');
        $venta->save();

        return redirect()->route('detalle_ventas.index', $venta->id)->with('success', 'Producto eliminado.');
    }
}
