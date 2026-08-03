<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $pedidos = Auth::user()->orders()->with(['items', 'address'])->latest()->get();
        return view('client.orders', compact('pedidos'));
    }
}