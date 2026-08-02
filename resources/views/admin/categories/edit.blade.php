@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="form-container">
    <h1 class="form-title">Editar: {{ $categoria->name }}</h1>
    
    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $categoria->name) }}">
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description', $categoria->description) }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full">Actualizar Cambios</button>
    </form>
</div>
@endsection