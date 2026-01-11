<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }

        .title {
            text-align: center;
            font-size: 26px;
            color: #0056b3;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header-logo {
            width: 120px;
            margin-bottom: 10px;
        }

        .totales {
            width: 100%;
            margin: 15px 0;
        }

        .totales td {
            border: 1px solid #999;
            padding: 6px;
            font-size: 14px;
            background: #f5f5f5;
        }

        table { width:100%; border-collapse: collapse; margin-top:10px;}
        th,td { border:1px solid #999; padding:6px; text-align:left; }
        th { background:#eee; }

        .chart {
            text-align: center;
            margin: 15px 0;
        }
        .chart img {
            width: 600px;
        }
    </style>
</head>
<body>

<div style="text-align:center;">
    <img src="{{ public_path('imagenes/logo.png') }}" class="header-logo">
</div>

<div class="title">Reporte de Ventas</div>

<p style="text-align:center;">
    <strong>Del:</strong> {{ $desde }}
    <strong>— Al:</strong> {{ $hasta }}
</p>

<table class="totales">
    <tr>
        <td><strong>Total vendido:</strong> ${{ number_format($totalVendido,2) }}</td>
        <td><strong>Ventas:</strong> {{ $cantidadVentas }}</td>
        <td><strong>Contado:</strong> ${{ number_format($totalContado,2) }}</td>
        <td><strong>Crédito:</strong> ${{ number_format($totalCredito,2) }}</td>
    </tr>
</table>


<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Tipo</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ventas as $v)
        <tr>
            <td>{{ $v->id_venta }}</td>
            <td>{{ $v->cliente->nombre ?? '' }}</td>
            <td>{{ $v->fecha }}</td>
            <td>${{ number_format($v->total,2) }}</td>
            <td>{{ ucfirst($v->tipo_pago) }}</td>
            <td>{{ ucfirst($v->estado) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
