<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #222; }
        .title { text-align: center; font-size: 26px; color: #0056b3; font-weight: bold; margin-bottom: 6px; }
        .header-logo { width: 120px; margin: 0 auto 6px; display:block; }
        .sub { text-align:center; margin-bottom:12px; color:#555; }
        .totales { width: 100%; margin: 12px 0; border-collapse: collapse; }
        .totales td { padding:8px; border:1px solid #ddd; background:#f7f9fc; font-size:14px; }
        table.main { width:100%; border-collapse: collapse; margin-top:6px;}
        table.main th, table.main td { border:1px solid #999; padding:6px; text-align:left; font-size:12px; }
        table.main th { background:#eee; }
        .low { background:#ffe6e6; } /* fila bajo stock */
    </style>
</head>
<body>

<div style="text-align:center;">
    <img src="{{ public_path('imagenes/logo.png') }}" class="header-logo" alt="Logo">
</div>

<div class="title">Reporte de Inventario</div>

<p class="sub">
    <strong>Generado:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    @if(!empty($search)) — <strong>Filtro:</strong> "{{ $search }}" @endif
</p>

<table class="totales">
    <tr>
        <td><strong>Productos:</strong> {{ $totalProductos }}</td>
        <td><strong>Total unidades:</strong> {{ number_format($totalStock,0) }}</td>
        <td><strong>Valor (mayoreo):</strong> ${{ number_format($totalValueMayoreo,2) }}</td>
        <td><strong>Valor (menudeo):</strong> ${{ number_format($totalValueMenudeo,2) }}</td>
    </tr>
</table>

<table class="main">
    <thead>
    <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Stock</th>
        <th>Unidad</th>
        <th>Precio mayoreo</th>
        <th>Precio menudeo</th>
        <th>Valor (mayoreo)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($productos as $p)
        <tr @if($p->stock < ($lowThreshold ?? 10)) class="low" @endif>
            <td>{{ $p->id_producto }}</td>
            <td>{{ $p->nombre_producto }}</td>
            <td>{{ $p->stock }}</td>
            <td>{{ $p->unidad }}</td>
            <td>${{ number_format($p->precio_mayoreo,2) }}</td>
            <td>${{ number_format($p->precio_menudeo,2) }}</td>
            <td>${{ number_format($p->stock * $p->precio_mayoreo,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p style="margin-top:12px; font-size:12px;">
    Productos bajo stock (&lt; {{ $lowThreshold ?? 10 }}): <strong style="color:#d00">{{ $lowStockCount }}</strong>
    — Valor total de los productos bajos: <strong>${{ number_format($lowStockValue,2) }}</strong>
</p>

</body>
</html>
