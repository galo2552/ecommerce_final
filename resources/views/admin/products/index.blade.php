@extends('layouts.app')

@section('title', 'Gestión de Productos - Admin')

@section('content')
<div class="admin-header">
    <h1 class="form-title" style="margin-bottom: 0;">Productos</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">Nuevo Producto</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Categorías</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $producto)
        <tr>
            <td>{{ $producto->id }}</td>
            <td><strong>{{ $producto->name }}</strong></td>
            <td>{{ $producto->brand }}</td>
            <td>${{ number_format($producto->price, 2, ',', '.') }}</td>
            <td>
                @forelse($producto->categories as $categoria)
                    <span class="badge-category">{{ $categoria->name }}</span>
                @empty
                    <span class="text-muted">Sin categoría</span>
                @endforelse
            </td>
            <td>
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-admin">Editar</a>
                <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-logout" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No hay productos cargados todavía.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
