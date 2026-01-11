<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Carbon\Carbon;
use DB;

// Para export (condicional)
use Maatwebsite\Excel\Facades\Excel;
use PDF; // si instalaste barryvdh/laravel-dompdf

class ReporteController extends Controller
{
    /**
     * Página principal del reporte de ventas.
     * Filtros por fecha desde/hasta, cliente y tipo_pago.
     */
    public function ventas(Request $request)
    {
        // filtros
        $desde = $request->input('desde') ? Carbon::parse($request->input('desde'))->toDateString() : now()->startOfMonth()->toDateString();
        $hasta = $request->input('hasta') ? Carbon::parse($request->input('hasta'))->toDateString() : now()->endOfMonth()->toDateString();
        $clienteId = $request->input('cliente_id') ?: null;
        $tipoPago = $request->input('tipo_pago') ?: null;

        // query base
        $query = Venta::with('cliente')
            ->whereBetween('fecha', [$desde, $hasta]);

        if ($clienteId) {
            $query->where('id_cliente', $clienteId);
        }

        if ($tipoPago) {
            $query->where('tipo_pago', $tipoPago);
        }

        // lista paginada para la tabla
        $ventas = $query->orderBy('fecha', 'desc')->paginate(15)->withQueryString();

        // totales y métricas
        $ventasResumenQuery = Venta::whereBetween('fecha', [$desde, $hasta]);
        if ($clienteId) $ventasResumenQuery->where('id_cliente', $clienteId);
        if ($tipoPago) $ventasResumenQuery->where('tipo_pago', $tipoPago);

        $totalVendido = (float) $ventasResumenQuery->sum('total');
        $cantidadVentas = (int) $ventasResumenQuery->count();
        $totalContado = (float) (clone $ventasResumenQuery)->where('tipo_pago', 'contado')->sum('total');
        $totalCredito = (float) (clone $ventasResumenQuery)->where('tipo_pago', 'credito')->sum('total');

        // datos para gráfica: ventas por día
        $ventasPorDia = Venta::select(
            DB::raw('fecha as fecha'),
            DB::raw('SUM(total) as total_dia'),
            DB::raw('COUNT(*) as ventas_count')
        )
            ->whereBetween('fecha', [$desde, $hasta])
            ->when($clienteId, fn($q)=> $q->where('id_cliente', $clienteId))
            ->when($tipoPago, fn($q)=> $q->where('tipo_pago', $tipoPago))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // preparar arrays para la gráfica (labels + datos)
        $labels = $ventasPorDia->pluck('fecha')->map(function($d) {
            return Carbon::parse($d)->format('Y-m-d');
        })->toArray();

        $dataVentas = $ventasPorDia->pluck('total_dia')->map(fn($v)=> (float)$v)->toArray();

        // lista de clientes para el filtro
        $clientes = Cliente::orderBy('nombre')->get();

        return view('reportes.ventas', compact(
            'ventas', 'totalVendido', 'cantidadVentas', 'totalContado', 'totalCredito',
            'labels', 'dataVentas', 'clientes', 'desde', 'hasta', 'clienteId', 'tipoPago'
        ));
    }



    /**
     * Exportar a PDF (opcional) - requiere barryvdh/laravel-dompdf
     */
    public function exportPdf(Request $request)
    {
        $desde = $request->input('desde') ? Carbon::parse($request->input('desde'))->toDateString() : now()->startOfMonth()->toDateString();
        $hasta = $request->input('hasta') ? Carbon::parse($request->input('hasta'))->toDateString() : now()->endOfMonth()->toDateString();
        $clienteId = $request->input('cliente_id') ?: null;
        $tipoPago = $request->input('tipo_pago') ?: null;

        // FILTRO PRINCIPAL
        $query = Venta::with('cliente')->whereBetween('fecha', [$desde, $hasta]);
        if ($clienteId) $query->where('id_cliente', $clienteId);
        if ($tipoPago) $query->where('tipo_pago', $tipoPago);

        $ventas = $query->orderBy('fecha')->get();

        // ----------- RESÚMENES PARA PDF -----------

        $ventasResumenQuery = Venta::whereBetween('fecha', [$desde, $hasta]);
        if ($clienteId) $ventasResumenQuery->where('id_cliente', $clienteId);
        if ($tipoPago) $ventasResumenQuery->where('tipo_pago', $tipoPago);

        $totalVendido = (float) $ventasResumenQuery->sum('total');
        $cantidadVentas = (int) $ventasResumenQuery->count();
        $totalContado = (float) (clone $ventasResumenQuery)->where('tipo_pago', 'contado')->sum('total');
        $totalCredito = (float) (clone $ventasResumenQuery)->where('tipo_pago', 'credito')->sum('total');

        // -----------------------------------------

        $pdf = PDF::loadView('reportes.ventas_pdf', compact(
            'ventas',
            'desde',
            'hasta',
            'clienteId',
            'tipoPago',
            'totalVendido',
            'cantidadVentas',
            'totalContado',
            'totalCredito'
        ));

        return $pdf->download('reporte_ventas_' . now()->format('Ymd_His') . '.pdf');
    }

}
