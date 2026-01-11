<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ClienteController,
    ProveedorController,
    ProductoController,
    RutaController,
    RutaRegistroController,
    AuthController,
    CreditoController,
    DetalleVentaController,
    EntregaController,
    PagoController,
    VentaController,
    ReporteController,
    ReporteInventarioController,
    ReporteProveedoresController,
    ReporteClientesController
};

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

// 🟢 Pon el calendario ANTES del resource
Route::get('/rutas/calendario', [RutaController::class, 'calendario'])->name('rutas.calendario');
Route::get('/rutas/eventos', [RutaController::class, 'eventos'])->name('rutas.eventos');

// Recursos principales
Route::resource('clientes', ClienteController::class);
// Route::resource('proveedores', ProveedorController::class);
Route::resource('productos', ProductoController::class);
Route::resource('rutas', RutaController::class);

Route::resource('proveedores', ProveedorController::class)->parameters([
    'proveedores' => 'proveedor'
]);

// Seguimiento diario
Route::prefix('rutas/{ruta_id}/registros')->group(function() {
    Route::get('/', [RutaRegistroController::class, 'index'])->name('ruta_registros.index');
    Route::get('/create', [RutaRegistroController::class, 'create'])->name('ruta_registros.create');
    Route::post('/', [RutaRegistroController::class, 'store'])->name('ruta_registros.store');
    Route::get('/{registro}/edit', [RutaRegistroController::class, 'edit'])->name('ruta_registros.edit');
    Route::put('/{registro}', [RutaRegistroController::class, 'update'])->name('ruta_registros.update');
    Route::delete('/{registro}', [RutaRegistroController::class, 'destroy'])->name('ruta_registros.destroy');
});

// Calendario de una ruta específica (opcional)
Route::get('/rutas/{ruta_id}/calendario', [RutaRegistroController::class, 'calendario'])->name('rutas_registros.calendario');

// 📅 Calendario general interactivo (FullCalendar)
Route::get('/rutas/calendario', [RutaController::class, 'calendario'])->name('rutas.calendario');
Route::get('/rutas/eventos', [RutaController::class, 'eventos'])->name('rutas.eventos');
Route::post('/rutas/actualizar-fecha/{id}', [RutaController::class, 'actualizarFecha'])->name('rutas.actualizarFecha');
Route::post('/rutas/crear-desde-calendario', [RutaController::class, 'crearDesdeCalendario'])->name('rutas.crearDesdeCalendario');

Route::get('/rutas/{id}/detalle', [RutaController::class, 'detalle'])->name('rutas.detalle');


// Ventas
Route::resource('ventas', VentaController::class);

// Detalle de Ventas
Route::resource('detalle_ventas', DetalleVentaController::class);

// Créditos
Route::resource('creditos', CreditoController::class);

// Pagos
Route::resource('pagos', PagoController::class);

// Entregas
Route::resource('entregas', EntregaController::class);

// Detalle de ventas
Route::get('/ventas/{venta}/detalle', [\App\Http\Controllers\DetalleVentaController::class, 'index'])->name('detalle_ventas.index');
Route::post('/ventas/{venta}/detalle', [\App\Http\Controllers\DetalleVentaController::class, 'store'])->name('detalle_ventas.store');
Route::delete('/detalle_ventas/{id}', [\App\Http\Controllers\DetalleVentaController::class, 'destroy'])->name('detalle_ventas.destroy');

Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
Route::get('/pagos/{id_credito}/create', [PagoController::class, 'create'])->name('pagos.create');
Route::post('/pagos/{id_credito}', [PagoController::class, 'store'])->name('pagos.store');

Route::prefix('reportes')->group(function () {
    Route::get('ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('ventas/export-pdf', [ReporteController::class, 'exportPdf'])->name('reportes.ventas.export.pdf'); // opcional
    Route::get('/reportes/ventas/pdf', [ReporteController::class, 'reporteVentasPDF'])
        ->name('reportes.ventas.pdf');
    Route::get('ventas/export-excel', [ReporteController::class, 'exportExcel'])->name('reportes.ventas.export.excel'); // opcional

});
Route::get('/reportes', function() {
    return view('reportes.index');
})->name('reportes.index');

Route::post('/reportes/ventas/pdf', [ReporteController::class, 'exportPdf'])
    ->name('reportes.ventas.pdf');

// Reportes - Inventario
Route::get('reportes/inventario', [\App\Http\Controllers\ReporteInventarioController::class, 'index'])
    ->name('reportes.inventario');

Route::get('reportes/inventario/export-pdf', [\App\Http\Controllers\ReporteInventarioController::class, 'exportPdf'])
    ->name('reportes.inventario.export.pdf');

Route::get('/reportes/proveedores', [ReporteProveedoresController::class, 'index'])->name('reportes.proveedores');
Route::get('/reportes/proveedores/export-pdf', [ReporteProveedoresController::class, 'exportPdf'])->name('reportes.proveedores.export.pdf');

// Reportes - Clientes
Route::get('reportes/clientes', [\App\Http\Controllers\ReporteClientesController::class, 'index'])
    ->name('reportes.clientes.index');

Route::get('reportes/clientes/export-pdf', [\App\Http\Controllers\ReporteClientesController::class, 'exportPdf'])
    ->name('reportes.clientes.export.pdf');


Route::get('reportes/clientes/{cliente}', [\App\Http\Controllers\ReporteClientesController::class, 'show'])
    ->name('reportes.clientes.show');


