<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Luna Commerce')</title>
    <meta name="description" content="A Laravel ecommerce storefront demo with products, cart, and checkout.">
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <div class="container nav-row">
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand-mark">LC</span>
                    <span>
                        <strong>Luna Commerce</strong>
                        <small>Laravel Ecommerce Demo</small>
                    </span>
                </a>

                <nav class="main-nav">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('products.index') }}">Shop</a>
                    <a href="{{ route('checkout.create') }}">Checkout</a>
                    @auth
                        <a href="{{ route('wishlist.index') }}">Wishlist</a>
                        <a href="{{ route('account.show') }}">My account</a>
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @endif
                    @endauth
                </nav>

                <div class="nav-actions">
                    @guest
                        <a class="auth-link" href="{{ route('login') }}">Log in</a>
                        <a class="auth-link auth-link-strong" href="{{ route('register') }}">Sign up</a>
                    @else
                        <span class="auth-greeting">Hi, {{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="auth-button">Log out</button>
                        </form>
                    @endguest

                    <a class="cart-pill" href="{{ route('cart.index') }}">
                        <span>Cart</span>
                        <strong>{{ $cartCount }}</strong>
                        <small>${{ number_format($cartSubtotal, 2) }}</small>
                    </a>

                    @auth
                        <a class="cart-pill wishlist-pill" href="{{ route('wishlist.index') }}">
                            <span>Wishlist</span>
                            <strong>{{ $wishlistCount }}</strong>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="container content-stack">
            @if (session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="flash error">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="container footer-row">
                <div>
                    <strong>Luna Commerce</strong>
                    <p>Built with Laravel, PHP, SQLite, sessions, and seeded catalog data.</p>
                </div>
                <div class="footer-links">
                    <a href="{{ route('products.index') }}">Browse products</a>
                    <a href="{{ route('cart.index') }}">View cart</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
