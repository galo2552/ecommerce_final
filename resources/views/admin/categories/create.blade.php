@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
<div class="form-container">
    <h1 class="form-title">Crear Categoría</h1>
    
    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}">
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description') }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full">Guardar</button>
    </form>
</div>
@endsection