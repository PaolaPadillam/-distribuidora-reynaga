<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;
use DB;
use PDF;

class ReporteInventarioController extends Controller
{
    /**
     * Página principal del reporte de inventario.
     * Filtros opcionales: search (nombre), proveedor_id
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $proveedorId = $request->input('proveedor_id');

        $query = Producto::with('proveedor');

        if ($search) {
            $query->where('nombre_producto', 'like', "%{$search}%");
        }

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $productos = $query->orderBy('nombre_producto')->paginate(20)->withQueryString();

        // Totales
        $allQuery = Producto::query();
        if ($search) $allQuery->where('nombre_producto', 'like', "%{$search}%");
        if ($proveedorId) $allQuery->where('proveedor_id', $proveedorId);

        $totalProductos = $allQuery->count();
        $totalStock = (float) $allQuery->sum('stock');
        $totalValueMayoreo = (float) $allQuery->select(DB::raw('SUM(stock * precio_mayoreo) as total'))->pluck('total')->first() ?? 0;
        $totalValueMenudeo = (float) $allQuery->select(DB::raw('SUM(stock * precio_menudeo) as total'))->pluck('total')->first() ?? 0;

        // Low stock (fijo: < 10)
        $lowThreshold = 10;
        $lowStockCount = (int) (clone $allQuery)->where('stock', '<', $lowThreshold)->count();
        $lowStockValue = (float) (clone $allQuery)->where('stock', '<', $lowThreshold)
            ->select(DB::raw('SUM(stock * precio_mayoreo) as total'))->pluck('total')->first() ?? 0;

        // Datos para gráfica (stock por producto) - top N para no saturar la gráfica
        $chartProducts = Producto::query()
            ->when($search, fn($q) => $q->where('nombre_producto', 'like', "%{$search}%"))
            ->when($proveedorId, fn($q) => $q->where('proveedor_id', $proveedorId))
            ->orderByDesc('stock')
            ->limit(25)
            ->get(['nombre_producto', 'stock']);


        $labels = $chartProducts->pluck('nombre_producto')->map(function($l){
            return mb_strimwidth($l, 0, 25, '...'); // acorta etiquetas largas
        })->toArray();
        $dataStock = $chartProducts->pluck('stock')->map(fn($s)=>(int)$s)->toArray();

        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('reportes.inventario', compact(
            'productos',
            'totalProductos',
            'totalStock',
            'totalValueMayoreo',
            'totalValueMenudeo',
            'lowThreshold',
            'lowStockCount',
            'lowStockValue',
            'labels',
            'dataStock',
            'proveedores',
            'search',
            'proveedorId'
        ));
    }

    /**
     * Exportar PDF bonito del inventario
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $proveedorId = $request->input('proveedor_id');

        $query = Producto::with('proveedor');
        if ($search) $query->where('nombre_producto', 'like', "%{$search}%");
        if ($proveedorId) $query->where('proveedor_id', $proveedorId);

        $productos = $query->orderBy('nombre_producto')->get();

        $totalProductos = $productos->count();
        $totalStock = $productos->sum('stock');
        $totalValueMayoreo = $productos->sum(fn($p)=> $p->stock * (float)$p->precio_mayoreo);
        $totalValueMenudeo = $productos->sum(fn($p)=> $p->stock * (float)$p->precio_menudeo);

        $lowThreshold = 10;
        $lowStockCount = $productos->where('stock', '<', $lowThreshold)->count();
        $lowStockValue = $productos->where('stock', '<', $lowThreshold)
            ->sum(fn($p)=> $p->stock * (float)$p->precio_mayoreo);

        $pdf = PDF::loadView('reportes.inventario_pdf', compact(
            'productos',
            'totalProductos',
            'totalStock',
            'totalValueMayoreo',
            'totalValueMenudeo',
            'lowThreshold',
            'lowStockCount',
            'lowStockValue',
            'search',
            'proveedorId'
        ));

        return $pdf->download('reporte_inventario_'.now()->format('Ymd_His').'.pdf');
    }
}
