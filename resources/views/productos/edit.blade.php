@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4"><i class="bi bi-pencil-square"></i> Editar Producto</h3>
        <div class="card p-4">
            <form action="{{ route('productos.update', $producto) }}" method="POST">
                @method('PUT')
                @include('productos._form')
            </form>
        </div>
    </div>
@endsection
