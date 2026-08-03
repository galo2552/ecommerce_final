@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="form-container" style="max-width: 500px;">
    <h1 class="form-title">Editar: {{ $producto->name }}</h1>

    @php
        $selected = old('categories', $producto->categories->pluck('id')->toArray());
    @endphp

    <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $producto->name) }}">
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Marca</label>
            <input type="text" name="brand" class="form-input" value="{{ old('brand', $producto->brand) }}">
            @error('brand')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description', $producto->description) }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" name="price" class="form-input" value="{{ old('price', $producto->price) }}">
            @error('price')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Imagen</label>
            @if($producto->image)
                <img src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" style="width:100px; border-radius:0.375rem; margin-bottom:0.5rem; display:block;">
            @endif
            <input type="file" name="image" class="form-input" accept="image/*">
            <span class="text-muted">Dejalo vacío para mantener la imagen actual.</span>
            @error('image')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Categorías</label>
            <div class="checkbox-group">
                @forelse($categories as $categoria)
                    <label class="checkbox-item">
                        <input type="checkbox" name="categories[]" value="{{ $categoria->id }}"
                            {{ in_array($categoria->id, $selected) ? 'checked' : '' }}>
                        {{ $categoria->name }}
                    </label>
                @empty
                    <p class="text-muted">No hay categorías creadas todavía. <a href="{{ route('categorias.create') }}">Creá una primero</a>.</p>
                @endforelse
            </div>
            @error('categories')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full">Actualizar Cambios</button>
    </form>
</div>
@endsection
