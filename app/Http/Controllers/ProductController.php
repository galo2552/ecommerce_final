<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->safe()->except('categories');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $producto = Product::create($data);
        $producto->categories()->sync($request->validated('categories'));

        return redirect()->route('productos.index')->with('success', 'Producto creado con éxito.');
    }

    public function edit(Product $producto)
    {
        $categories = Category::all();
        $producto->load('categories');
        return view('admin.products.edit', compact('producto', 'categories'));
    }

    public function update(ProductRequest $request, Product $producto)
    {
        $data = $request->safe()->except('categories');

        if ($request->hasFile('image')) {
            if ($producto->image) {
                Storage::disk('public')->delete($producto->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $producto->update($data);
        $producto->categories()->sync($request->validated('categories'));

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $producto)
    {
        if ($producto->image) {
            Storage::disk('public')->delete($producto->image);
        }

        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}