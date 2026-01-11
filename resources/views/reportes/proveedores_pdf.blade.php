<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Proveedores</title>
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

    </style>
</head>
<body>

<!-- LOGO -->
<div style="text-align:center;">
    <img src="{{ public_path('imagenes/logo.png') }}" class="header-logo">
</div>

<!-- TÍTULO -->
<div class="title">Reporte de Proveedores</div>

<!-- FILTRO APLICADO -->
@if($search)
    <p style="text-align:center;">
        <strong>Búsqueda:</strong> "{{ $search }}"
    </p>
@endif

<!-- TOTALES -->
<table class="totales">
    <tr>
        <td><strong>Total proveedores:</strong> {{ $totalProveedores }}</td>
        <td><strong>Total productos registrados:</strong> {{ $totalProductos }}</td>
    </tr>
</table>

<!-- TABLA -->
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Dirección</th>
        <th>Productos</th>
    </tr>
    </thead>
    <tbody>
    @foreach($proveedores as $p)
        <tr>
            <td>{{ $p->id_proveedor}}</td>
            <td>{{ $p->nombre }}</td>
            <td>{{ $p->telefono }}</td>
            <td>{{ $p->email }}</td> <!-- Ajusta si tu campo se llama distinto -->
            <td>{{ $p->direccion }}</td>
            <td>{{ $p->productos->count() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
