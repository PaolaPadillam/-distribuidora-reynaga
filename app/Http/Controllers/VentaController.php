<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $ventas = Venta::with('cliente')
            ->when($search, function ($query, $search) {
                $query->whereHas('cliente', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%");
                })
                    ->orWhere('tipo_pago', 'like', "%$search%")
                    ->orWhere('fecha', 'like', "%$search%");
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return view('ventas.index', compact('ventas', 'search'));
    }

    public function create()
    {
        $clientes = \App\Models\Cliente::all();
        $productos = \App\Models\Producto::all();
        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        // Validar datos del formulario
        $validated = $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'tipo_pago' => 'required|string|in:contado,credito',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'plazo_dias' => 'nullable|integer|min:1', // solo si hay crédito
        ]);

        // 💡 Determinar estado automáticamente según tipo de pago
        $estado = $validated['tipo_pago'] === 'contado' ? 'pagado' : 'pendiente';

        // Crear la venta
        $venta = \App\Models\Venta::create([
            'id_cliente' => $validated['id_cliente'],
            'fecha' => $validated['fecha'],
            'tipo_pago' => $validated['tipo_pago'],
            'estado' => $estado,
            'total' => 0, // se actualizará más adelante
        ]);

        $total = 0;

        // Guardar los detalles y actualizar stock
        foreach ($validated['productos'] as $productoData) {
            $producto = \App\Models\Producto::find($productoData['id_producto']);
            $subtotal = $productoData['cantidad'] * $productoData['precio_unitario'];

            // Crear detalle
            \App\Models\DetalleVenta::create([
                'id_venta' => $venta->id_venta,
                'id_producto' => $productoData['id_producto'],
                'cantidad' => $productoData['cantidad'],
                'precio_unitario' => $productoData['precio_unitario'],
                'subtotal' => $subtotal,
            ]);

            // Descontar stock
            $producto->stock -= $productoData['cantidad'];
            $producto->save();

            $total += $subtotal;
        }

        // Actualizar el total de la venta
        $venta->update(['total' => $total]);

        // 💳 Si es crédito → crear automáticamente el registro en la tabla creditos
        if ($venta->tipo_pago === 'credito') {
            $plazo = (int)($request->plazo_dias ?? 30);
            $fecha_vencimiento = \Carbon\Carbon::parse($venta->fecha)->addDays($plazo);

            \App\Models\Credito::create([
                'id_cliente' => $venta->id_cliente,
                'id_venta' => $venta->id_venta,
                'monto_total' => $total,
                'saldo_pendiente' => $total,
                'fecha_inicio' => $venta->fecha,
                'fecha_vencimiento' => $fecha_vencimiento,
                'estado' => 'activo',
            ]);
        }

        // 💰 Si es contado → registrar el pago total automáticamente
        if ($venta->tipo_pago === 'contado') {
            \App\Models\Pago::create([
                'id_credito' => null,
                'fecha_pago' => $venta->fecha,
                'monto_pago' => $venta->total,
                'metodo_pago' => 'efectivo', // puedes cambiarlo si quieres otro método
                'observaciones' => 'Pago al contado registrado automáticamente.',
            ]);
        }

        return redirect()
            ->route('ventas.index')
            ->with('success', 'La venta se registró correctamente.');
    }


    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'detalles.producto']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $clientes = Cliente::all();
        return view('ventas.edit', compact('venta', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        $validated = $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|string|in:pendiente,pagado,cancelado',
        ]);

        if ($venta->estado !== $validated['estado'] && $validated['estado'] === 'cancelado') {
            $venta->load('detalles');
            foreach ($venta->detalles as $detalle) {
                $producto = \App\Models\Producto::find($detalle->id_producto);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }
        }

        $venta->update($validated);

        return redirect()
            ->route('ventas.show', $venta->id_venta)
            ->with('success', 'La venta se actualizó correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->load('detalles');

        foreach ($venta->detalles as $detalle) {
            $producto = \App\Models\Producto::find($detalle->id_producto);
            if ($producto) {
                $producto->stock += $detalle->cantidad;
                $producto->save();
            }
        }

        $venta->delete();

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada y stock restaurado correctamente.');
    }
}
