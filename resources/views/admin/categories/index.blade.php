@extends('layouts.app')

@section('title', 'Gestión de Categorías - Admin')

@section('content')
<div class="admin-header">
    <h1 class="form-title" style="margin-bottom: 0;">Categorías</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">Nueva Categoría</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $categoria)
        <tr>
            <td>{{ $categoria->id }}</td>
            <td><strong>{{ $categoria->name }}</strong></td>
            <td>{{ $categoria->description }}</td>
            <td>
                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-admin">Editar</a>
                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-logout" onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection