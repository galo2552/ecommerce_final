<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function getProducts()
    {
        $products = Product::with('categories')->get();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    public function getProduct($id)
    {
        $product = Product::with('categories')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }

    public function getUserOrders()
    {
        $user = Auth::user();
        
        $orders = $user->orders()->with('items')->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }
}
