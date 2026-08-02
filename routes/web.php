<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categorias', \App\Http\Controllers\CategoryController::class)
         ->parameters(['categorias' => 'categoria']);

Route::prefix('api')->group(function () {
    // Endpoints públicos
    Route::get('/products', [\App\Http\Controllers\ApiController::class, 'getProducts']);
    Route::get('/products/{id}', [\App\Http\Controllers\ApiController::class, 'getProduct']);
    
    // Endpoint protegido (requiere estar logueado)
    Route::middleware('auth')->get('/orders', [\App\Http\Controllers\ApiController::class, 'getUserOrders']);
});
});