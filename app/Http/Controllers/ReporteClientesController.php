<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\Credito;
use Carbon\Carbon;
use DB;
use PDF;

class ReporteClientesController extends Controller
{
    /**
     * Página principal del reporte de clientes
     */
    public function index(Request $request)
    {
        // ===============================
        // FILTROS
        // ===============================
        $search = $request->input('search');
        $clienteId = $request->input('cliente_id') ?: null;

        $desde = $request->input('desde')
            ? Carbon::parse($request->input('desde'))->toDateString()
            : now()->subMonths(1)->toDateString();

        $hasta = $request->input('hasta')
            ? Carbon::parse($request->input('hasta'))->toDateString()
            : now()->toDateString();

        // ===============================
        // LISTA DE CLIENTES (para select)
        // ===============================
        $clientesLista = Cliente::orderBy('nombre')->get();

        // ===============================
        // TABLA DE CLIENTES (paginada)
        // ===============================
        $query = Cliente::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        if ($clienteId) {
            $query->where('id', $clienteId);
        }

        $clientes = $query->orderBy('nombre')
            ->paginate(15)->withQueryString();

        // ===============================
        // MÉTRICAS PRINCIPALES (totales)
        // ===============================
        $ventasBase = Venta::whereBetween('fecha', [$desde, $hasta]);

        if ($clienteId) {
            $ventasBase->where('id_cliente', $clienteId);
        }

        $totalVendido = (float) $ventasBase->sum('total');
        $cantidadVentas = (int) $ventasBase->count();
        $totalContado = (float) (clone $ventasBase)->where('tipo_pago', 'contado')->sum('total');
        $totalCredito = (float) (clone $ventasBase)->where('tipo_pago', 'credito')->sum('total');

        // ===============================
        // TOTALES POR TIPO DE CLIENTE
        // ===============================
        if ($clienteId) {
            $clienteSel = Cliente::find($clienteId);

            $totalMayoreo = $clienteSel->tipo_cliente === 'mayoreo' ? $totalVendido : 0;
            $totalMenudeo = $clienteSel->tipo_cliente === 'menudeo' ? $totalVendido : 0;
        } else {
            $totalMayoreo = (float) Venta::whereBetween('fecha', [$desde, $hasta])
                ->whereHas('cliente', fn($q) => $q->where('tipo_cliente', 'mayoreo'))
                ->sum('total');

            $totalMenudeo = (float) Venta::whereBetween('fecha', [$desde, $hasta])
                ->whereHas('cliente', fn($q) => $q->where('tipo_cliente', 'menudeo'))
                ->sum('total');
        }

        // ===============================
        // GRÁFICA (TOP clientes o cliente seleccionado)
        // ===============================

        // Si selecciona cliente → gráfica solo de sus meses
        if ($clienteId) {
            $top = Venta::select(
                DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"),
                DB::raw("SUM(total) as total_mes")
            )
                ->where('id_cliente', $clienteId)
                ->whereBetween('fecha', [$desde, $hasta])
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();

            $labels = $top->pluck('mes');
            $data = $top->pluck('total_mes');
        }
        // Si NO selecciona cliente → top compradores generales
        else {
            $top = Venta::select(
                'id_cliente',
                DB::raw('SUM(total) as total_cliente')
            )
                ->whereBetween('fecha', [$desde, $hasta])
                ->groupBy('id_cliente')
                ->orderByDesc('total_cliente')
                ->limit(10)
                ->get();

            $clientesTop = Cliente::whereIn('id', $top->pluck('id_cliente'))->get()->keyBy('id');

            $labels = [];
            $data = [];

            foreach ($top as $t) {
                $cliente = $clientesTop[$t->id_cliente] ?? null;
                $labels[] = $cliente ? mb_strimwidth($cliente->nombre, 0, 25, '...') : "Cliente {$t->id_cliente}";
                $data[] = (float) $t->total_cliente;
            }
        }

        // ===============================
        // RETORNO A LA VISTA
        // ===============================
        return view('reportes.clientes.index', compact(
            'clientes',
            'clientesLista',
            'labels',
            'data',
            'totalVendido',
            'cantidadVentas',
            'totalMayoreo',
            'totalMenudeo',
            'search',
            'clienteId',
            'desde',
            'hasta'
        ));
    }


    /**
     * Mostrar historial individual del cliente
     */
    public function show(Request $request, Cliente $cliente)
    {
        $desde = $request->input('desde')
            ? Carbon::parse($request->input('desde'))->toDateString()
            : now()->subYear()->toDateString();

        $hasta = $request->input('hasta')
            ? Carbon::parse($request->input('hasta'))->toDateString()
            : now()->toDateString();

        $ventas = Venta::with('detalles.producto')
            ->where('id_cliente', $cliente->id)
            ->whereBetween('fecha', [$desde, $hasta])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalComprado = (float) $ventas->sum('total');

        $creditos = Credito::where('id_cliente', $cliente->id)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $saldoPendienteTotal = (float) $creditos->sum('saldo_pendiente');

        // Gráfica mensual
        $ventasPorMes = Venta::select(
            DB::raw("DATE_FORMAT(fecha, '%Y-%m') as ym"),
            DB::raw("SUM(total) as total_mes")
        )
            ->where('id_cliente', $cliente->id)
            ->whereBetween('fecha', [$desde, $hasta])
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $labelsMes = $ventasPorMes->pluck('ym');
        $dataMes = $ventasPorMes->pluck('total_mes');

        return view('reportes.clientes.show', compact(
            'cliente',
            'ventas',
            'totalComprado',
            'creditos',
            'saldoPendienteTotal',
            'desde',
            'hasta',
            'labelsMes',
            'dataMes'
        ));
    }


    /**
     * Exportar PDF del reporte de clientes
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $clienteId = $request->input('cliente_id') ?: null;

        $desde = $request->input('desde')
            ? Carbon::parse($request->input('desde'))->toDateString()
            : now()->subMonths(1)->toDateString();

        $hasta = $request->input('hasta')
            ? Carbon::parse($request->input('hasta'))->toDateString()
            : now()->toDateString();

        // ==============================
        // FILTRO DE CLIENTES
        // ==============================
        $query = Cliente::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        if ($clienteId) {
            $query->where('id', $clienteId);
        }

        $clientes = $query->orderBy('nombre')->get();

        // 🔥 Total de clientes filtrados
        $totalClientes = $clientes->count();

        // 🔥 Total de compras del/los cliente(s)
        $totalCompras = Venta::whereBetween('fecha', [$desde, $hasta])
            ->whereIn('id_cliente', $clientes->pluck('id'))
            ->sum('total');

        // ==============================
        // GENERAR PDF
        // ==============================
        $pdf = PDF::loadView('reportes.clientes.pdf', compact(
            'clientes',
            'totalClientes',
            'totalCompras',
            'search',
            'clienteId',
            'desde',
            'hasta'
        ));

        return $pdf->download('reporte_clientes_' . now()->format('Ymd_His') . '.pdf');
    }

}
