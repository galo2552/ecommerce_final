@extends('layouts.app')

@section('title', $producto->name . ' - ZapasApp')

@section('content')
<div class="container product-detail-wrapper">
    
    <div style="margin-bottom: 2rem;">
        <a href="{{ url()->previous() }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver al catálogo
        </a>
    </div>

    <div class="product-detail-card">
        <div class="product-detail-image-container">
            @if($producto->image)
                <img src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" class="product-detail-img">
            @else
                <div class="product-image-placeholder product-detail-img">Sin imagen</div>
            @endif
        </div>
        
        <div class="product-detail-info">
            <p class="product-brand-tag">{{ $producto->brand }}</p>
            <h1 class="product-main-title">{{ $producto->name }}</h1>
            
            <div class="product-badges">
                @foreach($producto->categories as $categoria)
                    <span class="badge-category">{{ $categoria->name }}</span>
                @endforeach
            </div>

            <p class="product-main-price">
                ${{ number_format($producto->price, 2, ',', '.') }}
            </p>
            
            <p class="product-description">{{ $producto->description }}</p>
            
            @auth
                @if(Auth::user()->role === 'cliente')
                    <form action="{{ route('cart.store', $producto) }}" method="POST" class="cart-form">
                        @csrf
                        <div class="cart-inputs">
                            <div class="input-group">
                                <label class="form-label">Talle</label>
                                <input type="text" name="size" class="form-input" placeholder="Ej: 42" required>
                            </div>
                            <div class="input-group">
                                <label class="form-label">Cantidad</label>
                                <input type="number" name="quantity" class="form-input" value="1" min="1" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full btn-large">Agregar al Carrito</button>
                    </form>
                @endif
            @else
                <div class="alert alert-info">
                    <a href="{{ route('login') }}" class="alert-link">Iniciá sesión</a> para comprar.
                </div>
            @endauth
        </div>
    </div>

    <div class="reviews-section">
        <h2 class="section-title">Opiniones sobre este producto</h2>

        @auth
            @if(Auth::user()->role === 'cliente')
                <div class="form-container review-form-container">
                    <h3 class="form-title-small">Dejá tu reseña</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('reviews.store', $producto) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Calificación</label>
                            <select name="rating" class="form-input" required>
                                <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                                <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                                <option value="3">⭐⭐⭐ Bueno</option>
                                <option value="2">⭐⭐ Regular</option>
                                <option value="1">⭐ Malo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Comentario</label>
                            <textarea name="comment" class="form-input" rows="3" placeholder="¿Qué te parecieron estas zapas?" required></textarea>
                            @error('comment')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-secondary">Publicar opinión</button>
                    </form>
                </div>
            @endif
        @endauth

        <div class="reviews-grid">
            @forelse($producto->reviews as $review)
                <div class="review-card">
                    <div class="review-header">
                        <strong>{{ $review->user->name }}</strong>
                        <span class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    <p class="review-comment">{{ $review->comment }}</p>
                    
                    <div class="review-footer">
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-text-danger" onclick="return confirm('¿Estás seguro de que querés eliminar esta reseña?')">Eliminar</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">Todavía no hay opiniones. ¡Sé el primero en calificar este producto!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection