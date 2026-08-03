<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Auth::user()->wishlists()->with('categories')->get();
        return view('client.wishlist', compact('products'));
    }

    public function store(Product $producto)
    {
        Auth::user()->wishlists()->syncWithoutDetaching($producto->id);
        return back()->with('success', 'Agregado a tu lista de deseos.');
    }

    public function destroy(Product $producto)
    {
        Auth::user()->wishlists()->detach($producto->id);
        return back()->with('success', 'Quitado de tu lista de deseos.');
    }
}