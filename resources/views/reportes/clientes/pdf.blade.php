<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Clientes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .title { text-align:center; font-size:26px; color:#0056b3; font-weight:bold; margin-bottom:10px; }
        .header-logo { width:120px; margin-bottom:10px; }
        .totales { width:100%; margin:15px 0; }
        .totales td { border:1px solid #999; padding:6px; font-size:14px; background:#f5f5f5; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th,td { border:1px solid #999; padding:6px; text-align:left; }
        th { background:#eee; }
    </style>
</head>
<body>
<div style="text-align:center;">
    <img src="{{ public_path('imagenes/logo.png') }}" class="header-logo">
</div>

<div class="title">Reporte de Clientes</div>

<p style="text-align:center;">
    <strong>Del:</strong> {{ $desde ?? '' }}
    <strong>— Al:</strong> {{ $hasta ?? '' }}
</p>

<p><strong>Total de clientes filtrados:</strong> {{ $totalClientes }}</p>
<p><strong>Total de compras:</strong> ${{ number_format($totalCompras, 2) }}</p>



<table>
    <thead>
    <tr><th>Nombre</th><th>Teléfono</th><th>Email</th><th>Tipo</th><th>Saldo actual</th></tr>
    </thead>
    <tbody>
    @foreach($clientes as $c)
        <tr>
            <td>{{ $c->nombre }}</td>
            <td>{{ $c->telefono }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ ucfirst($c->tipo_cliente) }}</td>
            <td>${{ number_format($c->saldo_actual ?? 0,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
