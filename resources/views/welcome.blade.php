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
                <div>
                    @foreach($producto->categories as $categoria)
                        <span class="badge-category">{{ $categoria->name }}</span>
                    @endforeach
                </div>

                @auth
                    @if(Auth::user()->role === 'cliente')
                        <form action="{{ route('cart.store', $producto) }}" method="POST" style="margin-top:0.75rem; display:flex; gap:0.5rem;">
                            @csrf
                            <input type="text" name="size" placeholder="Talle" class="form-input" style="width:70px;" required>
                            <input type="number" name="quantity" value="1" min="1" class="form-input" style="width:65px;" required>
                            <button type="submit" class="btn btn-admin">Agregar al pedido</button>
                        </form>

                        @if(in_array($producto->id, $wishlistIds))
                            <form action="{{ route('wishlist.destroy', $producto) }}" method="POST" style="margin-top:0.75rem;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-logout w-full">Quitar de deseados</button>
                            </form>
                        @else
                            <form action="{{ route('wishlist.store', $producto) }}" method="POST" style="margin-top:0.75rem;">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full">Agregar a deseados</button>
                            </form>
                        @endif
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <p class="text-muted">No hay productos cargados todavía.</p>
    @endforelse
</div>
@endsection