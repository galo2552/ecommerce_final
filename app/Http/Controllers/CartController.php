<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;

class CartController extends Controller
{
    public function index()
    {
        $carrito = session('carrito', []);
        $total = collect($carrito)->sum(fn ($item) => $item['price'] * $item['quantity']);
        
        $direcciones = Auth::user()->addresses; 

        return view('client.cart', compact('carrito', 'total', 'direcciones'));
    }

    public function store(AddToCartRequest $request, Product $producto)
    {
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

    public function checkout(CheckoutRequest $request)
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return back()->with('error', 'Tu carrito está vacío.');
        }

        if ($request->filled('address_id')) {
            $direccionId = $request->address_id;
        } else {
            $direccion = Address::create([
                'user_id' => Auth::id(),
                'street' => $request->street,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
            ]);
            $direccionId = $direccion->id;
        }

        $total = collect($carrito)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $pedido = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $direccionId,
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