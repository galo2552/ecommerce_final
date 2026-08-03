<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public const TRANSICIONES_VALIDAS = [
        'pendiente' => ['pagado', 'cancelado'],
        'pagado'    => ['enviado', 'cancelado'],
        'enviado'   => ['entregado'],
        'entregado' => [],
        'cancelado' => [],
    ];

    public function index()
    {
        $pedidos = Order::with(['user', 'items'])->latest()->get();
        return view('admin.orders.index', [
            'pedidos' => $pedidos,
            'transiciones' => self::TRANSICIONES_VALIDAS,
        ]);
    }

    public function updateStatus(Request $request, Order $pedido)
    {
        $request->validate([
            'status' => 'required|in:pendiente,pagado,enviado,entregado,cancelado',
        ]);

        $nuevoEstado = $request->input('status');
        $permitidos = self::TRANSICIONES_VALIDAS[$pedido->status] ?? [];

        if (!in_array($nuevoEstado, $permitidos)) {
            return back()->with('error', "No se puede pasar de '{$pedido->status}' a '{$nuevoEstado}'.");
        }

        $pedido->update(['status' => $nuevoEstado]);

        return back()->with('success', 'Estado del pedido actualizado.');
    }
}