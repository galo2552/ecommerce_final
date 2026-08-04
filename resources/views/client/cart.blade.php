@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<h1 class="form-title" style="text-align:left;">Mi Carrito</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if(empty($carrito))
    <p class="text-muted">Tu carrito está vacío. Agregá productos desde el <a href="{{ url('/') }}">catálogo</a>.</p>
@else
    <table class="admin-table" style="margin-bottom:2rem;">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talle</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($carrito as $clave => $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['size'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>${{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</td>
                <td>
                    <form action="{{ route('cart.destroy', $clave) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-logout">Quitar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="order-total" style="margin-bottom:1.5rem;">Total: ${{ number_format($total, 2, ',', '.') }}</div>

    <div class="form-container" style="max-width:500px; margin-left:0;">
        <h2 class="form-title">Dirección de envío</h2>
        <form action="{{ route('cart.checkout') }}" method="POST">
            @csrf
            
            @if($direcciones->count() > 0)
                <div class="form-group">
                    <label class="form-label">Mis direcciones guardadas</label>
                    <select name="address_id" class="form-input" id="address_select">
                        <option value="">+ Cargar una nueva dirección...</option>
                        @foreach($direcciones as $dir)
                            <option value="{{ $dir->id }}">{{ $dir->street }}, {{ $dir->city }} ({{ $dir->zip_code }})</option>
                        @endforeach
                    </select>
                </div>
                <hr style="margin: 1.5rem 0; border: 1px dashed #cbd5e1;">
            @endif

            <div id="new_address_fields">
                <div class="form-group">
                    <label class="form-label">Calle y número</label>
                    <input type="text" name="street" class="form-input" value="{{ old('street') }}">
                    @error('street')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ciudad</label>
                    <input type="text" name="city" class="form-input" value="{{ old('city') }}">
                    @error('city')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Provincia</label>
                    <input type="text" name="province" class="form-input" value="{{ old('province') }}">
                    @error('province')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Código Postal</label>
                    <input type="text" name="zip_code" class="form-input" value="{{ old('zip_code') }}">
                    @error('zip_code')<span class="error-message">{{ $message }}</span>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full" style="margin-top: 1rem;">Confirmar Pedido</button>
        </form>
    </div>

    <script>
        const select = document.getElementById('address_select');
        const newFields = document.getElementById('new_address_fields');
        if(select) {
            select.addEventListener('change', function() {
                newFields.style.display = this.value !== '' ? 'none' : 'block';
            });
        }
    </script>
@endif
@endsection