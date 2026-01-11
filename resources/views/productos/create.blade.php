@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4"><i class="bi bi-box-seam"></i> Nuevo Producto</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card p-4">
            <form action="{{ route('productos.store') }}" method="POST">
                @include('productos._form')
            </form>
        </div>
    </div>
@endsection
