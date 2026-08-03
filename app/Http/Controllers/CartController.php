<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carrito = session('carrito', []);
        $total = collect($carrito)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('client.cart', compact('carrito', 'total'));
    }

    public function store(Request $request, Product $producto)
    {
        $request->validate([
            'size' => ['required', 'string', 'max:10'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $carrito = session('carrito', []);
        $clave = $producto->id . '_' . $request->size;

        if (isset($carrito[$clave])) {
            $carrito[$clave]['quantity'] += (int) $request->quantity;
        } else {
            $carrito[$clave] = [
                'product_id' => $producto->id,
                'name' => $producto->name,
                'price' => $producto->price,
                'size' => $request->size,
                'quantity' => (int) $request->quantity,
            ];
        }

        session(['carrito' => $carrito]);

        return back()->with('success', 'Producto agregado a tu pedido.');
    }

    public function destroy(string $clave)
    {
        $carrito = session('carrito', []);
        unset($carrito[$clave]);
        session(['carrito' => $carrito]);

        return back()->with('success', 'Producto quitado del pedido.');
    }

    public function checkout(Request $request)
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return back()->with('error', 'Tu carrito está vacío.');
        }

        $request->validate([
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:20'],
        ]);

        $direccion = Address::create([
            'user_id' => Auth::id(),
            'street' => $request->street,
            'city' => $request->city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
        ]);

        $total = collect($carrito)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $pedido = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $direccion->id,
            'total' => $total,
            'status' => 'pendiente',
        ]);

        foreach ($carrito as $item) {
            $pedido->items()->attach($item['product_id'], [
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'size' => $item['size'],
            ]);
        }

        session()->forget('carrito');

        return redirect()->route('pedidos.mios')->with('success', 'Pedido realizado con éxito.');
    }
}