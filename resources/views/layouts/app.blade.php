<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ecommerce de Zapatillas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="main-header">
        <div class="container header-container">
            <a href="{{ url('/') }}" class="logo">ZapasApp</a>
            <nav class="main-nav">
                <a href="{{ url('/') }}" class="nav-link">Catálogo</a>
                
                @guest
                    <a href="{{ route('login') }}" class="nav-link">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                @endguest

                @auth
                    @if(Auth::user()->role === 'cliente')
                        <a href="{{ route('cart.index') }}" class="nav-link">Carrito</a>
                        <a href="{{ route('pedidos.mios') }}" class="nav-link">Mis Pedidos</a>
                        <a href="{{ route('wishlist.index') }}" class="nav-link">Lista de Deseos</a>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('categorias.index') }}" class="btn btn-admin">Categorías</a>
                        <a href="{{ route('productos.index') }}" class="btn btn-admin">Productos</a>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-admin">Pedidos</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="form-logout">
                        @csrf
                        <button type="submit" class="btn-logout">
                            Salir ({{ Auth::user()->name }})
                        </button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    <main class="main-content container">
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="container footer-container">
            <p>&copy; {{ date('Y') }} ZapasApp. Todos los derechos reservados.</p>
            <p class="footer-subtext">Desarrollo de Aplicación Web con Laravel - Trabajo Final</p>
        </div>
    </footer>
</body>
</html>