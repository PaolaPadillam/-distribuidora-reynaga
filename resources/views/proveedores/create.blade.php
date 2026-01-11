@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3 class="text-primary"><i class="bi bi-truck"></i> Nuevo Proveedor</h3>
        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('proveedores.store') }}" method="POST">
                    @include('proveedores._form')
                </form>
            </div>
        </div>
    </div>
@endsection
