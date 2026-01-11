<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Producto;
use PDF;

class ReporteProveedoresController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Filtro de proveedores
        $query = Proveedor::query();

        if ($search) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        // Lista paginada
        $proveedores = $query->orderBy('nombre')->paginate(15)->withQueryString();

        // Totales generales
        $totalProveedores = Proveedor::count();
        $totalProductos = Producto::count();

        // Productos por proveedor
        $queryStats = Proveedor::query()->withCount('productos');

        if ($search) {
            $queryStats->where('nombre', 'like', "%{$search}%");
        }

        $proveedorStats = $queryStats->get();


        $proveedorLabels = $proveedorStats->pluck('nombre')->toArray();
        $proveedorCounts = $proveedorStats->pluck('productos_count')->toArray();

        return view('reportes.proveedores', compact(
            'proveedores',
            'search',
            'totalProveedores',
            'totalProductos',
            'proveedorLabels',
            'proveedorCounts'
        ));
    }

    public function exportPdf(Request $request)
    {
        $search = $request->input('search');

        $query = Proveedor::query();
        if ($search) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        $proveedores = $query->orderBy('nombre')->get();
        $totalProveedores = Proveedor::count();
        $totalProductos = Producto::count();

        $pdf = PDF::loadView('reportes.proveedores_pdf', compact(
            'proveedores',
            'totalProveedores',
            'totalProductos',
            'search'
        ));

        return $pdf->download('reporte_proveedores_'.now()->format('Ymd_His').'.pdf');
    }
}
