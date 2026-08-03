@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<h1 class="form-title">Mis Pedidos</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@forelse($pedidos as $pedido)
    <div class="order-card">
        <div class="order-header">
            <div>
                <strong>Pedido #{{ $pedido->id }}</strong>
                <span class="text-muted">{{ $pedido->created_at->format('d/m/Y') }}</span>
            </div>
            <span class="badge-status badge-status-{{ $pedido->status }}">{{ ucfirst($pedido->status) }}</span>
        </div>

        <ul class="order-items">
            @foreach($pedido->items as $item)
                <li>{{ $item->pivot->quantity }} x {{ $item->name }} (talle {{ $item->pivot->size }}) — ${{ number_format($item->pivot->unit_price, 2, ',', '.') }}</li>
            @endforeach
        </ul>

        <div class="order-total">Total: ${{ number_format($pedido->total, 2, ',', '.') }}</div>
    </div>
@empty
    <p class="text-muted">Todavía no realizaste ningún pedido.</p>
@endforelse
@endsection