<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function (\Illuminate\Http\Request $request) {
    $categories = \App\Models\Category::all();

    $products = \App\Models\Product::with('categories')
        ->when($request->filled('categoria'), function ($query) use ($request) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->query('categoria'));
            });
        })
        ->latest()
        ->get();

    $wishlistIds = auth()->check()
        ? auth()->user()->wishlists()->pluck('products.id')->toArray()
        : [];

    return view('welcome', compact('products', 'categories', 'wishlistIds'));
});

Route::get('/producto/{producto}', function (\App\Models\Product $producto) {
    $producto->load('reviews.user', 'categories');
    return view('client.products.show', compact('producto'));
})->name('producto.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{producto}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{producto}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/mis-pedidos', [\App\Http\Controllers\OrderController::class, 'index'])->name('pedidos.mios');

    Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{producto}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{producto}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/mis-pedidos', [\App\Http\Controllers\OrderController::class, 'index'])->name('pedidos.mios');

    Route::get('/carrito', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/{producto}', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::delete('/carrito/{clave}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/carrito-confirmar', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    Route::post('/producto/{producto}/resenas', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categorias', \App\Http\Controllers\CategoryController::class)
         ->parameters(['categorias' => 'categoria']);

    Route::resource('productos', \App\Http\Controllers\ProductController::class)
         ->parameters(['productos' => 'producto']);

    Route::get('/pedidos', [\App\Http\Controllers\AdminOrderController::class, 'index'])
        ->name('admin.pedidos.index');
    Route::put('/pedidos/{pedido}/estado', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])
        ->name('admin.pedidos.updateStatus');
        
    Route::delete('/resenas/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});

Route::prefix('api')->group(function () {
    // Endpoints públicos
    Route::get('/products', [\App\Http\Controllers\ApiController::class, 'getProducts']);
    Route::get('/products/{id}', [\App\Http\Controllers\ApiController::class, 'getProduct']);

    // Endpoint protegido (requiere estar logueado)
    Route::middleware('auth')->get('/orders', [\App\Http\Controllers\ApiController::class, 'getUserOrders']);
});