@extends('layouts.app')

@section('title', 'Inicio - ZapasApp')

@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="color: var(--primary-color); font-size: 2.2rem;">Catálogo ZapasApp</h1>
    <p style="color: var(--text-light);">El ecommerce de zapatillas número uno de Norberto de la Riestra.</p>
</div>

<div class="category-filters">
    <a href="{{ url('/') }}" class="btn {{ request('categoria') ? 'btn-admin' : 'btn-primary' }}">Todas</a>
    @foreach($categories as $categoria)
        <a href="{{ url('/?categoria=' . $categoria->id) }}"
           class="btn {{ request('categoria') == $categoria->id ? 'btn-primary' : 'btn-admin' }}">
            {{ $categoria->name }}
        </a>
    @endforeach
</div>

<div class="product-grid">
    @forelse($products as $producto)
        <a href="{{ route('producto.show', $producto) }}" class="product-card-link">
            <div class="product-card">
                @if($producto->image)
                    <img src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" class="product-image">
                @else
                    <div class="product-image product-image-placeholder">Sin imagen</div>
                @endif
                
                <div class="product-info">
                    <p class="product-brand">{{ $producto->brand }}</p>
                    <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">{{ $producto->name }}</h3>
                    <p class="product-price">${{ number_format($producto->price, 2, ',', '.') }}</p>
                    
                    <div>
                        @foreach($producto->categories as $categoria)
                            <span class="badge-category">{{ $categoria->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </a>
    @empty
        <p class="text-muted">No hay productos cargados todavía.</p>
    @endforelse
</div>
@endsection