@extends('layouts.app')

@section('title', 'Gestión de Pedidos - Admin')

@section('content')
<h1 class="form-title" style="text-align:left;">Pedidos</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Ítems</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Cambiar estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $pedido)
        <tr>
            <td>{{ $pedido->id }}</td>
            <td>{{ $pedido->user->name }}</td>
            <td>
                @foreach($pedido->items as $item)
                    <div>{{ $item->pivot->quantity }} x {{ $item->name }}</div>
                @endforeach
            </td>
            <td>${{ number_format($pedido->total, 2, ',', '.') }}</td>
            <td><span class="badge-status badge-status-{{ $pedido->status }}">{{ ucfirst($pedido->status) }}</span></td>
            <td>
                @php $opciones = $transiciones[$pedido->status] ?? []; @endphp
                @if(count($opciones))
                    <form action="{{ route('admin.pedidos.updateStatus', $pedido) }}" method="POST" style="display:flex; gap:0.5rem;">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-input" style="width:auto;">
                            @foreach($opciones as $opcion)
                                <option value="{{ $opcion }}">{{ ucfirst($opcion) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-admin">Actualizar</button>
                    </form>
                @else
                    <span class="text-muted">Sin cambios posibles</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No hay pedidos cargados todavía.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection