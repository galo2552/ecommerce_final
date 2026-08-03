@extends('layouts.app')

@section('title', 'Mi Lista de Deseos')

@section('content')
<h1 class="form-title">Mi Lista de Deseos</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="product-grid">
    @forelse($products as $producto)
        <div class="product-card">
            @if($producto->image)
                <img src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" class="product-image">
            @else
                <div class="product-image product-image-placeholder">Sin imagen</div>
            @endif
            <div class="product-info">
                <h3>{{ $producto->name }}</h3>
                <p class="product-brand">{{ $producto->brand }}</p>
                <p class="product-price">${{ number_format($producto->price, 2, ',', '.') }}</p>
                <form action="{{ route('wishlist.destroy', $producto) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-logout w-full">Quitar</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted">Todavía no agregaste productos a tu lista de deseos.</p>
    @endforelse
</div>
@endsection